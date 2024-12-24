let currentIndex = 0; // Current slide index
const slides = document.querySelectorAll('.slides img');
const totalSlides = slides.length;

// Function to show a specific slide
function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.toggle('active', i === index);
    });
}

// Function to change slides manually
function changeSlide(direction) {
    currentIndex = (currentIndex + direction + totalSlides) % totalSlides;
    showSlide(currentIndex);
}

// Function to move slides automatically
function autoSlide() {
    currentIndex = (currentIndex + 1) % totalSlides;
    showSlide(currentIndex);
}

// Start auto sliding every 3 seconds
let slideInterval = setInterval(autoSlide, 3000);

// Add event listeners to navigation buttons
document.querySelector('.prev').addEventListener('click', () => {
    clearInterval(slideInterval); // Pause auto sliding
    changeSlide(-1);
    slideInterval = setInterval(autoSlide, 3000); // Restart auto sliding
});

document.querySelector('.next').addEventListener('click', () => {
    clearInterval(slideInterval); // Pause auto sliding
    changeSlide(1);
    slideInterval = setInterval(autoSlide, 3000); // Restart auto sliding
});
