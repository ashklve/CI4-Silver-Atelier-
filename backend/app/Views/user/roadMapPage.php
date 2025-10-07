<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Road Map - Silver Atelier</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'warm-brown': '#57564F',
                        'sage-green': '#7A7A73',
                        'cream-beige': '#DDDAD0',
                        'light-cream': '#F8F3CE'
                    },
                    fontFamily: {
                        'serif': ['Playfair Display', 'Georgia', 'serif'],
                        'sans': ['Inter', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans bg-light-cream min-h-screen">
    <!-- Header -->
    <nav class="bg-light-cream/90 backdrop-blur-md border-b border-cream-beige/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0">
                    <a href="#" class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0">
                            <img src="/images/salogo.png" alt="Silver Atelier Logo" class="w-full h-full object-cover">
                        </div>
                        <span class="font-serif text-2xl font-bold text-warm-brown">Silver Atelier</span>
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <button onclick="openAddModal()" class="bg-warm-brown text-light-cream px-4 py-2 rounded-lg hover:bg-sage-green transition-colors duration-300 font-medium">
                        <i class="fas fa-plus mr-2"></i>Add Item
                    </button>
                    <a href="<?= base_url('/') ?>" class="text-warm-brown hover:text-sage-green transition-colors duration-300 font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Home
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Page Header -->
        <div class="mb-12">
            <h1 class="font-serif text-5xl font-black text-warm-brown mb-4">Road Map</h1>
            <p class="text-xl text-sage-green mb-6">Development plan and upcoming features for Silver Atelier</p>
            
            <!-- Filter Buttons -->
            <div class="flex flex-wrap gap-3 mt-6">
                <button id="filter-all" onclick="filterStatus('all')" class="filter-btn px-4 py-2 rounded-full font-semibold transition-all duration-300 bg-warm-brown text-light-cream">
                    All
                </button>
                <button id="filter-done" onclick="filterStatus('done')" class="filter-btn px-4 py-2 rounded-full font-semibold transition-all duration-300 bg-cream-beige text-warm-brown hover:bg-green-500 hover:text-white">
                    Done
                </button>
                <button id="filter-planned" onclick="filterStatus('planned')" class="filter-btn px-4 py-2 rounded-full font-semibold transition-all duration-300 bg-cream-beige text-warm-brown hover:bg-purple-500 hover:text-white">
                    Planned
                </button>
                <button id="filter-backlog" onclick="filterStatus('backlog')" class="filter-btn px-4 py-2 rounded-full font-semibold transition-all duration-300 bg-cream-beige text-warm-brown hover:bg-gray-400 hover:text-white">
                    Backlog
                </button>
            </div>
        </div>

        <!-- Roadmap Items Container -->
        <div id="roadmap-container" class="space-y-6"></div>

        <!-- Empty State -->
        <div id="empty-state" class="text-center py-16 hidden">
            <i class="fas fa-clipboard-list text-6xl text-sage-green mb-4"></i>
            <h3 class="font-serif text-2xl font-bold text-warm-brown mb-2">No roadmap items yet</h3>
            <p class="text-sage-green mb-6">Start by adding your first roadmap item</p>
            <button onclick="openAddModal()" class="bg-warm-brown text-light-cream px-6 py-3 rounded-lg hover:bg-sage-green transition-colors duration-300 font-medium">
                <i class="fas fa-plus mr-2"></i>Add First Item
            </button>
        </div>

        <!-- Legend -->
        <div class="mt-12 bg-cream-beige rounded-2xl p-6">
            <h3 class="font-serif text-xl font-bold text-warm-brown mb-4">Status Legend</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-green-500 rounded"></div>
                    <span class="text-sage-green text-sm">Done - Feature completed</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-purple-500 rounded"></div>
                    <span class="text-sage-green text-sm">Planned - Coming soon</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-4 h-4 bg-gray-400 rounded"></div>
                    <span class="text-sage-green text-sm">Backlog - Future</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
        <div class="bg-light-cream rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 id="modal-title" class="font-serif text-3xl font-bold text-warm-brown">Add Roadmap Item</h2>
                    <button onclick="closeModal()" class="text-sage-green hover:text-warm-brown transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                
                <form id="roadmap-form" class="space-y-4">
                    <input type="hidden" id="item-id">
                    
                    <div>
                        <label class="block text-warm-brown font-semibold mb-2">Title</label>
                        <input type="text" id="item-title" required class="w-full px-4 py-2 rounded-lg border-2 border-cream-beige focus:border-warm-brown focus:outline-none">
                    </div>
                    
                    <div>
                        <label class="block text-warm-brown font-semibold mb-2">Description</label>
                        <textarea id="item-description" required rows="4" class="w-full px-4 py-2 rounded-lg border-2 border-cream-beige focus:border-warm-brown focus:outline-none"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-warm-brown font-semibold mb-2">Status</label>
                            <select id="item-status" required class="w-full px-4 py-2 rounded-lg border-2 border-cream-beige focus:border-warm-brown focus:outline-none">
                                <option value="done">Done</option>
                                <option value="planned">Planned</option>
                                <option value="backlog">Backlog</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-warm-brown font-semibold mb-2">Priority</label>
                            <select id="item-priority" required class="w-full px-4 py-2 rounded-lg border-2 border-cream-beige focus:border-warm-brown focus:outline-none">
                                <option value="High">High</option>
                                <option value="Medium">Medium</option>
                                <option value="Low">Low</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="flex-1 bg-warm-brown text-light-cream px-6 py-3 rounded-lg hover:bg-sage-green transition-colors duration-300 font-semibold">
                            <i class="fas fa-save mr-2"></i>Save
                        </button>
                        <button type="button" onclick="closeModal()" class="flex-1 bg-cream-beige text-warm-brown px-6 py-3 rounded-lg hover:bg-sage-green hover:text-light-cream transition-colors duration-300 font-semibold">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-warm-brown text-light-cream py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; 2025 Silver Atelier. All rights reserved.</p>
        </div>
    </footer>

    <script>
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

        // close modal on outside click
        document.getElementById('modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // initial render
        renderRoadmap();
    </script>
</body>
</html>