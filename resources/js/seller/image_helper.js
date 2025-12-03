
export function setupImagePreview(inputId, previewId) {
    const imageInput = document.getElementById(inputId);
    const previewContainer = document.getElementById(previewId);

    if (!imageInput || !previewContainer) return;

    imageInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                previewContainer.innerHTML = `
                    <img src="${e.target.result}" class="w-32 h-32 object-cover rounded" alt="Preview">
                `;
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.innerHTML = '';
        }
    });
}
