const sharp = require('sharp');
const fs = require('fs');
const path = require('path');

const imageDirectory = path.join(__dirname, '../public/assets/images');
const optimizedDirectory = path.join(__dirname, '../public/assets/images/optimized');

// Créer le dossier optimized s'il n'existe pas
if (!fs.existsSync(optimizedDirectory)) {
    fs.mkdirSync(optimizedDirectory, { recursive: true });
}

async function optimizeImage(filePath) {
    const fileName = path.basename(filePath);
    const outputPath = path.join(optimizedDirectory, fileName);
    
    try {
        await sharp(filePath)
            .resize(1200, 1200, { 
                fit: 'inside',
                withoutEnlargement: true
            })
            .webp({ quality: 80 })
            .toFile(outputPath.replace(/\.[^/.]+$/, '.webp'));
            
        console.log(`Optimized: ${fileName}`);
    } catch (error) {
        console.error(`Error optimizing ${fileName}:`, error);
    }
}

async function processDirectory() {
    const files = fs.readdirSync(imageDirectory);
    
    for (const file of files) {
        if (file.match(/\.(jpg|jpeg|png|gif)$/i)) {
            await optimizeImage(path.join(imageDirectory, file));
        }
    }
}

processDirectory();
