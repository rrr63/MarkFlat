<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PollController extends AbstractController
{
    private string $contentDir;
    private RequestStack $requestStack;

    public function __construct(string $projectDir, RequestStack $requestStack)
    {
        $this->contentDir = $projectDir . '/content';
        $this->requestStack = $requestStack;
    }

    #[Route('/api/poll/vote', name: 'poll_vote', methods: ['POST'])]
    public function vote(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['pollId'], $data['option'], $data['filePath'])) {
            return new JsonResponse(['error' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
        }

        // Check if user has already voted
        $session = $this->requestStack->getSession();
        $votedPolls = $session->get('voted_polls', []);
        if (in_array($data['pollId'], $votedPolls)) {
            return new JsonResponse(['error' => 'Already voted'], Response::HTTP_BAD_REQUEST);
        }

        $filePath = $this->contentDir . '/' . $data['filePath'];
        if (!file_exists($filePath)) {
            return new JsonResponse(['error' => 'File not found: ' . $filePath], Response::HTTP_NOT_FOUND);
        }

        $content = file_get_contents($filePath);
        
        // Extract frontmatter and content
        if (!preg_match('/^---\n(.*?)\n---/s', $content, $matches)) {
            return new JsonResponse(['error' => 'No frontmatter found'], Response::HTTP_BAD_REQUEST);
        }

        // Parse YAML frontmatter
        $frontmatter = Yaml::parse($matches[1]);
        
        // Get poll configuration to know the number of options
        preg_match_all('/\[POLL\]\s*\n(.*?)\n\[\/POLL\]/s', $content, $matches);
        $numOptions = 3; // Default to 3 options
        foreach ($matches[1] as $pollConfig) {
            $config = json_decode(trim($pollConfig), true);
            if ($config && isset($config['id']) && $config['id'] === $data['pollId'] && isset($config['options'])) {
                $numOptions = count($config['options']);
                break;
            }
        }

        // Initialize or update poll data
        if (!isset($frontmatter['polls'])) {
            $frontmatter['polls'] = [];
        }
        if (!isset($frontmatter['polls'][$data['pollId']])) {
            $frontmatter['polls'][$data['pollId']] = ['votes' => array_fill(0, $numOptions, 0)];
        }

        // Ensure votes array has correct size
        $votes = &$frontmatter['polls'][$data['pollId']]['votes'];
        if (count($votes) !== $numOptions) {
            $votes = array_replace(
                array_fill(0, $numOptions, 0),
                array_slice($votes, 0, $numOptions)
            );
        }

        // Increment vote count
        if ($data['option'] >= 0 && $data['option'] < $numOptions) {
            $votes[$data['option']]++;
            
            // Mark poll as voted in session
            $votedPolls[] = $data['pollId'];
            $session->set('voted_polls', $votedPolls);
        } else {
            return new JsonResponse(['error' => 'Invalid option index'], Response::HTTP_BAD_REQUEST);
        }

        // Convert frontmatter back to YAML
        $newFrontmatter = Yaml::dump($frontmatter, 4, 2);
        
        // Update the file
        $newContent = preg_replace('/^---\n.*?\n---/s', "---\n" . $newFrontmatter . "---", $content);
        file_put_contents($filePath, $newContent);

        return new JsonResponse([
            'success' => true,
            'results' => $votes
        ]);
    }

    #[Route('/api/poll/{pollId}/results', name: 'poll_results', methods: ['GET'])]
    public function getResults(Request $request, string $pollId): JsonResponse
    {
        $filePath = $this->contentDir . '/' . $request->query->get('filePath');
        if (!$filePath || !file_exists($filePath)) {
            return new JsonResponse(['error' => 'File not found'], Response::HTTP_NOT_FOUND);
        }

        $content = file_get_contents($filePath);
        
        // Extract and parse frontmatter
        if (!preg_match('/^---\n(.*?)\n---/s', $content, $matches)) {
            return new JsonResponse(['error' => 'No frontmatter found'], Response::HTTP_BAD_REQUEST);
        }

        $frontmatter = Yaml::parse($matches[1]);
        
        if (!isset($frontmatter['polls'][$pollId])) {
            return new JsonResponse(['error' => 'Poll not found'], Response::HTTP_NOT_FOUND);
        }

        // Check if user has already voted
        $session = $this->requestStack->getSession();
        $votedPolls = $session->get('voted_polls', []);
        $hasVoted = in_array($pollId, $votedPolls);

        return new JsonResponse([
            'data' => $frontmatter['polls'][$pollId],
            'hasVoted' => $hasVoted
        ]);
    }
}
