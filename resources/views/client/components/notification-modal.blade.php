<!-- Notification Modal -->
<div id="notification-modal" class="fixed inset-0 z-50 hidden overflow-y-auto flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background overlay/backdrop -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

    <!-- Modal panel -->
    <div class="relative bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-lg w-full mx-auto">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div id="notification-icon" class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                    <!-- Icon will be inserted via JavaScript -->
                </div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="notification-title">
                        <!-- Title will be inserted via JavaScript -->
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500" id="notification-message">
                            <!-- Message will be inserted via JavaScript -->
                        </p>
                    </div>
                    <div class="mt-2">
                        <p class="text-xs text-gray-400" id="notification-time">
                            <!-- Time will be inserted via JavaScript -->
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="button" id="close-notification-modal" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                Close
            </button>
        </div>
    </div>
</div> 