// ========================================
// GALERIE IMAGEKIT
// Photos Noir & Blanc
// ========================================

// ⚠️ Mets ici ton vrai endpoint ImageKit si celui-ci est différent.
const imageKitBase = "https://ik.imagekit.io/photosnoiretblanc/photos-noir-blanc/";

// Liste de toutes les photos
const photos = [
  "Image1.jpg",
  "Image2.jpg",
  "Image3.tif",
  "Image4.jpg",
  "Image5.tif",
  "Image6.jpg",
  "Image7.jpg",
  "Image8.jpg",
  "Image9.jpg",
  "Image10.jpg",
  "Image11.jpg",
  "Image12.tif",
  "Image13.jpg",
  "Image14.jpg",
  "Image15.jpg",
  "Image16.tif",
  "Image17.jpg",
  "Image18.tif",
  "Image19.tif",
  "Image20.jpg",
  "Image21.jpg",
  "Image22.tif",
  "Image23.jpg",
  "Image24.jpg",
  "Image25.tif",
  "Image26.jpg",
  "Image27.tif",
  "Image28.tif",
  "Image29.jpg",
  "Image30.jpg",
  "Image31.jpg",
  "Image32.jpg",
  "Image33.jpg",
  "Image34.jpg",
  "Image35.jpg",
  "Image36.jpg"
];

// On attend que la page HTML soit chargée
document.addEventListener("DOMContentLoaded", () => {

  // Récupération de la galerie
  const gallery = document.getElementById("gallery");

  // Vérification
  if (!gallery) {
    console.error(
      'Galerie introuvable : ajoute id="gallery" à la section .sheet-full'
    );
    return;
  }

  // Création automatique des 36 photos
  photos.forEach((file, index) => {

    // 01, 02, 03...
    const number = String(index + 1).padStart(2, "0");

    // ========================================
    // LIEN
    // ========================================

    const link = document.createElement("a");

    link.className = "frame";
    link.href = "tarifs.html";


    // ========================================
    // IMAGE
    // ========================================

    const img = document.createElement("img");

    // ImageKit :
    // w-1000 = largeur maximum 1000px
    // q-auto = qualité automatique
    // f-auto = format automatique
    img.src =
      `${imageKitBase}tr:w-1000,q-auto,f-auto/${encodeURIComponent(file)}`;

    img.alt = `Photographie noir et blanc N°${number}`;

    // Charge les images seulement lorsqu'elles
    // approchent de la zone visible
    img.loading = "lazy";

    // Décodage asynchrone
    img.decoding = "async";


    // ========================================
    // NUMÉRO
    // ========================================

    const num = document.createElement("span");

    num.className = "fnum mono";
    num.textContent = `N°${number}`;


    // ========================================
    // DESCRIPTION
    // ========================================

    const caption = document.createElement("span");

    caption.className = "fcap";
    caption.textContent = "Tirage — dès 82 CHF";


    // ========================================
    // AJOUT DANS LA GALERIE
    // ========================================

    link.appendChild(img);
    link.appendChild(num);
    link.appendChild(caption);

    gallery.appendChild(link);

  });

});