# Sondages dans MarkFlatCMS

Les sondages permettent d'ajouter des questions interactives dans vos articles et de collecter les votes des utilisateurs. Les résultats sont stockés directement dans le frontmatter du fichier Markdown.

## Syntaxe

Pour ajouter un sondage, utilisez la syntaxe suivante :

```markdown
[POLL]
{
  "question": "Votre question ?",
  "options": ["Option 1", "Option 2", "Option 3"]
}
[/POLL]
```

### Paramètres

#### Requis
- `question` : La question à poser
- `options` : Un tableau des options de réponse possibles

#### Optionnel
- `id` : Un identifiant unique pour le sondage. Si non fourni, un ID sera généré automatiquement à partir de la question ou avec un timestamp unique.

### Génération automatique des IDs

Si l'ID n'est pas spécifié, le système en génère un automatiquement selon les règles suivantes :
1. Utilise la question comme base (convertie en minuscules, espaces remplacés par des tirets)
2. Si l'ID est déjà utilisé, ajoute un suffixe numérique
3. En dernier recours, génère un ID basé sur un timestamp et un nombre aléatoire

## Stockage des votes

Les votes sont automatiquement stockés dans le frontmatter du fichier Markdown sous la clé `polls`. Par exemple :

```yaml
---
title: Mon Article
polls:
  quelle-est-votre-couleur-preferee:
    votes: [5, 2, 3]
---
```

Le tableau `votes` contient le nombre de votes pour chaque option, dans l'ordre où elles apparaissent dans la configuration du sondage.

## Gestion des votes

- Un utilisateur ne peut voter qu'une seule fois par sondage dans une session
- Si l'utilisateur a déjà voté, les résultats sont affichés automatiquement
- Les votes sont enregistrés dans la session de l'utilisateur
- L'interface est automatiquement mise à jour pour désactiver le vote une fois que l'utilisateur a voté

## Affichage des résultats

Les résultats du sondage sont automatiquement affichés :
- Après qu'un utilisateur a voté
- Immédiatement si l'utilisateur a déjà voté dans sa session actuelle

Les résultats montrent :
- Le nombre de votes pour chaque option
- Le pourcentage pour chaque option
- Le nombre total de votes

## Thèmes

Les sondages utilisent les classes Tailwind CSS suivantes que vous pouvez personnaliser dans votre thème :

- `container` : Le conteneur principal du sondage
- `content` : Le texte de la question et des options
- `button` : Le bouton de vote
- `error` : Les messages d'erreur

## Exemple complet

```markdown
---
title: Mon Article
polls:
  couleur-preferee:
    votes: [10, 5, 8]
---

# Les couleurs

[POLL]
{
  "question": "Quelle est votre couleur préférée ?",
  "options": ["Bleu", "Rouge", "Vert"]
}
[/POLL]
```

## Limitations actuelles

- Les votes sont limités par session (un utilisateur peut voter à nouveau dans une nouvelle session)
- Les sondages sont en lecture seule une fois qu'un vote a été enregistré dans la session
- Le nombre d'options est fixe après la création du sondage
