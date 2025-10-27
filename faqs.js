const accordionButtons = document.querySelectorAll('.accordion-button');

accordionButtons.forEach(button => {
    button.addEventListener('click', () => {
    const accordionContent = button.nextElementSibling;
    const icon = button.querySelector('i');

    accordionContent.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
    });
});

feather.replace();