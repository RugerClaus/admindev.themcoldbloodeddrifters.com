document.addEventListener("DOMContentLoaded", () => {
    const grid = document.getElementById("carousel_grid");
    const statusBox = document.getElementById("carousel_status");
    const modal = document.getElementById("carousel_modal");
    const form = document.getElementById("carousel_form");
    const closeModalBtn = document.getElementById("close_carousel_modal");

    let editingId = null;

    async function fetchList() {
        try {
            const res = await fetch("/api/carousel");
            if (!res.ok) throw new Error("Failed to load carousel");
            const list = await res.json();
            render(list);
        } catch (err) {
            console.error(err);
            statusBox.textContent = "Error loading carousel";
            statusBox.classList.remove("hidden");
        }
    }

    function render(list) {
        grid.innerHTML = "";
        list.forEach(item => {
            const card = document.createElement("div");
            card.className = "carousel-card";
            card.innerHTML = `
                <img src="${item.image || ""}" alt="${item.alt || ""}">
                <div class="meta">
                    <span>${item.caption || ""}</span>
                    <span>${item.sort_order || ""}</span>
                </div>
                <div class="carousel-card-overlay">
                    <button class="edit" data-id="${item.id}"><span class="icon">✏️</span></button>
                    <button class="delete" data-id="${item.id}"><span class="icon">🗑️</span></button>
                </div>
            `;
            grid.appendChild(card);
        });
        bindCardEvents();
    }

    function bindCardEvents() {
        grid.querySelectorAll(".edit").forEach(btn => {
            btn.addEventListener("click", () => openModal(btn.dataset.id));
        });
        grid.querySelectorAll(".delete").forEach(btn => {
            btn.addEventListener("click", () => deleteItem(btn.dataset.id));
        });
    }

    function openModal(id) {
        editingId = id;
        modal.classList.remove("hidden");
        if (id) {
            fetch(`/api/carousel/${id}`)
                .then(res => res.json())
                .then(item => {
                    document.getElementById("carousel_id").value = item.id;
                    document.getElementById("carousel_alt").value = item.alt || "";
                    document.getElementById("carousel_caption").value = item.caption || "";
                    document.getElementById("carousel_sort").value = item.sort_order || "";
                });
        } else {
            form.reset();
            document.getElementById("carousel_id").value = "";
        }
    }

    async function deleteItem(id) {
        if (!confirm("Delete this image?")) return;
        const res = await fetch(`/api/carousel/${id}`, { method: "DELETE" });
        if (res.ok) fetchList();
    }

    form.addEventListener("submit", async e => {
        e.preventDefault();
        const fd = new FormData(form);
        const id = fd.get("id");
        const method = id ? "POST" : "PUT";
        const url = id ? `/api/carousel/${id}` : "/api/carousel";

        const res = await fetch(url, {
            method,
            body: fd
        });

        if (res.ok) {
            modal.classList.add("hidden");
            fetchList();
        }
    });

    closeModalBtn.addEventListener("click", () => {
        modal.classList.add("hidden");
    });

    fetchList();
});
