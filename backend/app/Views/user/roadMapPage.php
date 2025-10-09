<?php
?>
<!DOCTYPE html>
<html lang="en">
<?= view('components/head', ['title' => 'Road Map - Silver Atelier']) ?>

<body class="font-sans bg-light-cream min-h-screen">
    <?= view('components/header') ?>

    <!-- Main Content -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-12">
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

    <?= view('components/footer') ?>
    <script src="<?= base_url('js/roadmap.js') ?>"></script>
</body>
</html>