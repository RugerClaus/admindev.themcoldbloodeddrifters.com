const api_url = 'https://apidev.themcoldbloodeddrifters.com';

async function loadGallery() {
    const container = document.getElementById('carousel_control');
    const loading = document.getElementById('loading');
    loading.style.display = 'block';
    container.innerHTML = '';

    try {
        const response = await fetch(`${api_url}/carousel`);
        if (!response.ok) throw new Error('Network response was not ok');
        const data = await response.json();

        // Render data
        data.forEach(item => {
            const card = document.createElement('div');
            card.classList.add('carousel_image');

            // Store data as dataset for easy retrieval
            card.dataset.id = item.id;
            card.dataset.src = item.src;
            card.dataset.caption = item.caption;
            card.dataset.blurb = item.blurb;

            card.innerHTML = `<img src="${item.src}" alt="${item.caption}" style="max-width:100%;">`;

            // Add click listener here so it works immediately
            card.addEventListener('click', () => openEditor(item));

            container.appendChild(card);
        });
    } catch (error) {
        container.innerHTML = `<p style="color:red;">Failed to load data: ${error.message}</p>`;
    } finally {
        loading.style.display = 'none';
    }
}

function openEditor(item) {
    const editor = document.createElement('section');
    editor.classList.add('page');
    editor.innerHTML = `
        <button class="close_section_button">Close</button>
        <img src="${item.src}" id="image_${item.id}" alt="${item.caption}" style="max-width:100%;">
        <textarea>${item.caption}</textarea>
        <textarea>${item.blurb}</textarea>
        <button type="submit"></button>
    `;

    body.appendChild(editor);

    // Close button
    editor.querySelector('.close_editor').addEventListener('click', () => {
        editor.remove();
    });
}

loadGallery();
setInterval(loadGallery, 10000);
