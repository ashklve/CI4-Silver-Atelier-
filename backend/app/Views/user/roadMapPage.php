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
                    <a href="<?= base_url('/') ?>" class="flex items-center space-x-2">
                        <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0">
                            <img src="/images/salogo.png" alt="Silver Atelier Logo" class="w-full h-full object-cover">
                        </div>
                        <span class="font-serif text-2xl font-bold text-warm-brown">Silver Atelier</span>
                    </a>
                </div>
                <div>
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

        <!-- Roadmap Items -->
        <div class="space-y-6">
            <!-- Moodboard (Done) -->
            <div class="roadmap-item bg-cream-beige rounded-2xl shadow-lg p-6 border-l-4 border-green-500" data-status="done">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-serif text-2xl font-bold text-warm-brown">Moodboard</h3>
                            <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">Done</span>
                        </div>
                        <p class="text-sage-green leading-relaxed mb-3">
                            Visual inspiration board showcasing design aesthetics and brand identity.
                        </p>
                        <span class="text-sm text-sage-green"><i class="fas fa-exclamation-circle mr-1"></i>Priority: <span class="font-semibold text-warm-brown">High</span></span>
                    </div>
                </div>
            </div>

            <!-- Login & Signup UI (Done) -->
            <div class="roadmap-item bg-cream-beige rounded-2xl shadow-lg p-6 border-l-4 border-green-500" data-status="done">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-serif text-2xl font-bold text-warm-brown">Login & Signup UI</h3>
                            <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">Done</span>
                        </div>
                        <p class="text-sage-green leading-relaxed mb-3">
                            Frontend design and user interface for login and signup pages.
                        </p>
                        <span class="text-sm text-sage-green"><i class="fas fa-exclamation-circle mr-1"></i>Priority: <span class="font-semibold text-warm-brown">High</span></span>
                    </div>
                </div>
            </div>

            <!-- User Authentication Backend (Planned) -->
            <div class="roadmap-item bg-cream-beige rounded-2xl shadow-lg p-6 border-l-4 border-purple-500" data-status="planned">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-serif text-2xl font-bold text-warm-brown">Login & Signup Authentication</h3>
                            <span class="px-3 py-1 bg-purple-500 text-white text-xs font-semibold rounded-full">Planned</span>
                        </div>
                        <p class="text-sage-green leading-relaxed mb-3">
                            Backend implementation with secure password management, session handling, and user verification.
                        </p>
                        <span class="text-sm text-sage-green"><i class="fas fa-exclamation-circle mr-1"></i>Priority: <span class="font-semibold text-warm-brown">High</span></span>
                    </div>
                </div>
            </div>

            <!-- Product Management (Planned) -->
            <div class="roadmap-item bg-cream-beige rounded-2xl shadow-lg p-6 border-l-4 border-purple-500" data-status="planned">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-serif text-2xl font-bold text-warm-brown">Product Management (CRUD)</h3>
                            <span class="px-3 py-1 bg-purple-500 text-white text-xs font-semibold rounded-full">Planned</span>
                        </div>
                        <p class="text-sage-green leading-relaxed mb-3">
                            Create, Read, Update, and Delete products with images, descriptions, and pricing.
                        </p>
                        <span class="text-sm text-sage-green"><i class="fas fa-exclamation-circle mr-1"></i>Priority: <span class="font-semibold text-warm-brown">High</span></span>
                    </div>
                </div>
            </div>

            <!-- Shopping Cart (Planned) -->
            <div class="roadmap-item bg-cream-beige rounded-2xl shadow-lg p-6 border-l-4 border-purple-500" data-status="planned">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-serif text-2xl font-bold text-warm-brown">Shopping Cart System (CRUD)</h3>
                            <span class="px-3 py-1 bg-purple-500 text-white text-xs font-semibold rounded-full">Planned</span>
                        </div>
                        <p class="text-sage-green leading-relaxed mb-3">
                            Add, update, remove items from cart and manage quantities before checkout.
                        </p>
                        <span class="text-sm text-sage-green"><i class="fas fa-exclamation-circle mr-1"></i>Priority: <span class="font-semibold text-warm-brown">High</span></span>
                    </div>
                </div>
            </div>

            <!-- Order Management (Planned) -->
            <div class="roadmap-item bg-cream-beige rounded-2xl shadow-lg p-6 border-l-4 border-purple-500" data-status="planned">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-serif text-2xl font-bold text-warm-brown">Order Management (CRUD)</h3>
                            <span class="px-3 py-1 bg-purple-500 text-white text-xs font-semibold rounded-full">Planned</span>
                        </div>
                        <p class="text-sage-green leading-relaxed mb-3">
                            Create orders, view order history, update order status, and track deliveries.
                        </p>
                        <span class="text-sm text-sage-green"><i class="fas fa-exclamation-circle mr-1"></i>Priority: <span class="font-semibold text-warm-brown">Medium</span></span>
                    </div>
                </div>
            </div>

            <!-- Admin Dashboard (Backlog) -->
            <div class="roadmap-item bg-cream-beige rounded-2xl shadow-lg p-6 border-l-4 border-gray-400" data-status="backlog">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-serif text-2xl font-bold text-warm-brown">Admin Dashboard</h3>
                            <span class="px-3 py-1 bg-gray-400 text-white text-xs font-semibold rounded-full">Backlog</span>
                        </div>
                        <p class="text-sage-green leading-relaxed mb-3">
                            Dashboard for managing products, orders, and viewing sales statistics.
                        </p>
                        <span class="text-sm text-sage-green"><i class="fas fa-exclamation-circle mr-1"></i>Priority: <span class="font-semibold text-warm-brown">Medium</span></span>
                    </div>
                </div>
            </div>

            <!-- Payment Integration (Backlog) -->
            <div class="roadmap-item bg-cream-beige rounded-2xl shadow-lg p-6 border-l-4 border-gray-400" data-status="backlog">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="font-serif text-2xl font-bold text-warm-brown">Payment Integration</h3>
                            <span class="px-3 py-1 bg-gray-400 text-white text-xs font-semibold rounded-full">Backlog</span>
                        </div>
                        <p class="text-sage-green leading-relaxed mb-3">
                            Integrate payment gateway for secure online transactions.
                        </p>
                        <span class="text-sm text-sage-green"><i class="fas fa-exclamation-circle mr-1"></i>Priority: <span class="font-semibold text-warm-brown">Low</span></span>
                    </div>
                </div>
            </div>
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

    <!-- Footer -->
    <footer class="bg-warm-brown text-light-cream py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; 2025 Silver Atelier. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function filterStatus(status) {
            const items = document.querySelectorAll('.roadmap-item');
            const buttons = document.querySelectorAll('.filter-btn');
            
            // reset all buttons
            buttons.forEach(btn => {
                btn.classList.remove('bg-warm-brown', 'text-light-cream', 'bg-green-500', 'bg-purple-500', 'bg-gray-400', 'text-white');
                btn.classList.add('bg-cream-beige', 'text-warm-brown');
            });
            
            // highlight active button
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
            
            // filter items
            items.forEach(item => {
                if (status === 'all' || item.dataset.status === status) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>