let roadmapItems = [
    {
        id: 1,
        title: 'Moodboard',
        description: 'Visual inspiration board showcasing design aesthetics and brand identity.',
        status: 'done',
        priority: 'High'
    },
    {
        id: 2,
        title: 'Login & Signup UI',
        description: 'Frontend design and user interface for login and signup pages.',
        status: 'done',
        priority: 'High'
    },
    {
        id: 3,
        title: 'Login & Signup Authentication',
        description: 'Backend implementation with secure password management, session handling, and user verification.',
        status: 'planned',
        priority: 'High'
    },
    {
        id: 4,
        title: 'Product Management (CRUD)',
        description: 'Create, Read, Update, and Delete products with images, descriptions, and pricing.',
        status: 'planned',
        priority: 'High'
    },
    {
        id: 5,
        title: 'Shopping Cart System (CRUD)',
        description: 'Add, update, remove items from cart and manage quantities before checkout.',
        status: 'planned',
        priority: 'High'
    },
    {
        id: 6,
        title: 'Order Management (CRUD)',
        description: 'Create orders, view order history, update order status, and track deliveries.',
        status: 'planned',
        priority: 'Medium'
    },
    {
        id: 7,
        title: 'Admin Dashboard',
        description: 'Dashboard for managing products, orders, and viewing sales statistics.',
        status: 'backlog',
        priority: 'Medium'
    },
    {
        id: 8,
        title: 'Payment Integration',
        description: 'Integrate payment gateway for secure online transactions.',
        status: 'backlog',
        priority: 'Low'
    }
];

let currentFilter = 'all';
let nextId = 9;

function getStatusColor(status) {
    const colors = {
        done: 'green-500',
        planned: 'purple-500',
        backlog: 'gray-400'
    };
    return colors[status] || 'gray-400';
}

function getStatusLabel(status) {
    const labels = {
        done: 'Done',
        planned: 'Planned',
        backlog: 'Backlog'
    };
    return labels[status] || status;
}

function renderRoadmap() {
    const container = document.getElementById('roadmap-container');
    const emptyState = document.getElementById('empty-state');
    
    const filteredItems = currentFilter === 'all' 
        ? roadmapItems 
        : roadmapItems.filter(item => item.status === currentFilter);
    
    if (filteredItems.length === 0) {
        container.innerHTML = '';
        emptyState.classList.remove('hidden');
        return;
    }
    
    emptyState.classList.add('hidden');
    
    container.innerHTML = filteredItems.map(item => {
        const color = getStatusColor(item.status);
        return `
            <div class="roadmap-item bg-cream-beige rounded-2xl shadow-lg p-6 border-l-4 border-${color}" data-status="${item.status}">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-serif text-2xl font-bold text-warm-brown">${item.title}</h3>
                            <span class="px-3 py-1 bg-${color} text-white text-xs font-semibold rounded-full">${getStatusLabel(item.status)}</span>
                        </div>
                        <p class="text-sage-green leading-relaxed mb-3">
                            ${item.description}
                        </p>
                        <span class="text-sm text-sage-green"><i class="fas fa-exclamation-circle mr-1"></i>Priority: <span class="font-semibold text-warm-brown">${item.priority}</span></span>
                    </div>
                    <div class="flex gap-2 ml-4">
                        <button onclick="editItem(${item.id})" class="text-warm-brown hover:text-sage-green transition-colors" title="Edit">
                            <i class="fas fa-edit text-xl"></i>
                        </button>
                        <button onclick="deleteItem(${item.id})" class="text-red-500 hover:text-red-700 transition-colors" title="Delete">
                            <i class="fas fa-trash text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function filterStatus(status) {
    currentFilter = status;
    const buttons = document.querySelectorAll('.filter-btn');
    
    buttons.forEach(btn => {
        btn.classList.remove('bg-warm-brown', 'text-light-cream', 'bg-green-500', 'bg-purple-500', 'bg-gray-400', 'text-white');
        btn.classList.add('bg-cream-beige', 'text-warm-brown');
    });
    
    const activeBtn = document.getElementById('filter-' + status);
    activeBtn.classList.remove('bg-cream-beige', 'text-warm-brown');
    
    if (status === 'all') {
        activeBtn.classList.add('bg-warm-brown', 'text-light-cream');
    } else if (status === 'done') {
        activeBtn.classList.add('bg-green-500', 'text-white');
    } else if (status === 'planned') {
        activeBtn.classList.add('bg-purple-500', 'text-white');
    } else if (status === 'backlog') {
        activeBtn.classList.add('bg-gray-400', 'text-white');
    }
    
    renderRoadmap();
}

function openAddModal() {
    document.getElementById('modal-title').textContent = 'Add Roadmap Item';
    document.getElementById('roadmap-form').reset();
    document.getElementById('item-id').value = '';
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modal').classList.add('flex');
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
    document.getElementById('modal').classList.remove('flex');
}

function editItem(id) {
    const item = roadmapItems.find(i => i.id === id);
    if (!item) return;
    
    document.getElementById('modal-title').textContent = 'Edit Roadmap Item';
    document.getElementById('item-id').value = item.id;
    document.getElementById('item-title').value = item.title;
    document.getElementById('item-description').value = item.description;
    document.getElementById('item-status').value = item.status;
    document.getElementById('item-priority').value = item.priority;
    
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modal').classList.add('flex');
}

function deleteItem(id) {
    if (confirm('Are you sure you want to delete this item?')) {
        roadmapItems = roadmapItems.filter(item => item.id !== id);
        renderRoadmap();
    }
}

document.getElementById('roadmap-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('item-id').value;
    const item = {
        id: id ? parseInt(id) : nextId++,
        title: document.getElementById('item-title').value,
        description: document.getElementById('item-description').value,
        status: document.getElementById('item-status').value,
        priority: document.getElementById('item-priority').value
    };
    
    if (id) {
        const index = roadmapItems.findIndex(i => i.id === parseInt(id));
        if (index !== -1) {
            roadmapItems[index] = item;
        }
    } else {
        roadmapItems.push(item);
    }
    
    closeModal();
    renderRoadmap();
});

document.getElementById('modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

renderRoadmap();