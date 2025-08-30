(() => {
  const grid = document.getElementById('carousel_grid');
  const statusEl = document.getElementById('carousel_status');

  const modal = document.getElementById('carousel_modal');
  const form = document.getElementById('carousel_form');
  const closeBtn = document.getElementById('close_carousel_modal');
  const addBtn = document.getElementById('add_carousel_image');

  const idField = document.getElementById('carousel_id');
  const capField = document.getElementById('carousel_caption');
  const blurbField = document.getElementById('carousel_blurb'); 
  const imgField = document.getElementById('carousel_image');


  const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  function flash(msg) {
    statusEl.textContent = msg;
    statusEl.classList.remove('hidden');
    setTimeout(() => {
      statusEl.textContent = '';
      statusEl.classList.add('hidden');
    }, 1800);
  }

  function openModal(data = null) {
    form.reset();
    idField.value = '';
    if (data) {
      idField.value = data.id;
      capField.value = data.caption ?? '';
      blurbField.value = data.blurb ?? '';
    }
    modal.style.display = 'flex'
  }

  function closeModal() {
    modal.style.display = 'none';
  }

  async function fetchList() {
    const res = await fetch('/carousel/list', { headers: { 'Accept': 'application/json' }});
    if (!res.ok) throw new Error('Failed to load carousel');
    return await res.json();
  }

  function cardTemplate(item) {
    return `
      <div class="carousel_card" data-id="${item.id}">
        <img src="${item.src}" alt="${item.caption ?? ''}">
        <div class="carousel_card_overlay">
          <button class="edit_btn" title="Edit">
            <img class="icon" src="/assets/icons/pencil.png" alt="Edit">
          </button>
          <button class="delete_btn" title="Delete">
            <img class="icon" src="/assets/icons/trash.png" alt="Delete">
          </button>
        </div>
      </div>
    `;
  }

  function addCardTemplate() {
    return `
      <div class="carousel_card add_card">
        <div class="add_card_content">
          <span>+</span>
        </div>
      </div>
    `;
  }

  function bindCardEvents(card) {
    card.addEventListener('touchstart', () => {
      card.classList.toggle('touch_active');
    });

    if (card.classList.contains('add_card')) {
      card.addEventListener('click', () => openModal());
      return;
    }

    const editBtn = card.querySelector('.edit_btn');
    const delBtn  = card.querySelector('.delete_btn');

    editBtn.addEventListener('click', async e => {
      e.stopPropagation();
      const id = card.dataset.id;
      try {
        const res = await fetch(`/carousel/read/${id}`, { headers: { 'Accept':'application/json' }});
        const item = await res.json();
        openModal(item);
      } catch (err) {
        console.error(err);
        alert('Failed to load item.');
      }
    });

    delBtn.addEventListener('click', async e => {
      e.stopPropagation();
      const id = card.dataset.id;
      if (!confirm('Delete this image?')) return;
      try {
        const res = await fetch('/carousel/delete', {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ id })
        });
        const data = await res.json();
        if (data.success) {
          card.remove();
          flash('Deleted.');
        } else {
          alert(data.message || 'Delete failed.');
        }
      } catch (err) {
        console.error(err);
        alert('Error deleting image.');
      }
    });
  }

  function render(list) {
    grid.innerHTML = list.map(cardTemplate).join('') + addCardTemplate();
    grid.querySelectorAll('.carousel_card').forEach(bindCardEvents);
  }

  addBtn?.addEventListener('click', () => openModal());
  closeBtn?.addEventListener('click', closeModal);
  modal?.addEventListener('click', e => {
    if (e.target === modal) closeModal();
  });

  form?.addEventListener('submit', async e => {
    e.preventDefault();
    const fd = new FormData(form);
    const hasId = !!fd.get('id');
    try {
      const url = hasId ? '/carousel/update' : '/carousel/create';
      const res = await fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf },
        body: fd
      });
      const data = await res.json();
      if (!data.success) throw new Error(data.message || 'Save failed');
      closeModal();
      flash(hasId ? 'Updated!' : 'Created!');
      const list = await fetchList();
      render(list);
    } catch (err) {
      console.error(err);
      alert(err.message || 'Error saving.');
    }
  });

  (async () => {
    try {
      const list = await fetchList();
      render(list);
    } catch (err) {
      console.error(err);
      flash('Failed to load carousel.');
    }
  })();

  async function refreshCarousel() {
    try {
      const list = await fetchList();
      render(list);
    } catch (err) {
      console.error(err);
      flash('Failed to refresh carousel.');
    }
  }

  refreshCarousel();

  setInterval(refreshCarousel, 5000);
})();
