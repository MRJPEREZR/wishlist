/* wishlist.js */

document.addEventListener('DOMContentLoaded', function() {
    // 排序功能：绑定排序按钮事件
    const sortAsc = document.getElementById('sort-asc');
    const sortDesc = document.getElementById('sort-desc');

    if (sortAsc && sortDesc) {
        sortAsc.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Sort by Price Ascending');
            // TODO: 根据需求实现前端排序或 AJAX 请求
        });

        sortDesc.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Sort by Price Descending');
            // TODO: 根据需求实现前端排序或 AJAX 请求
        });
    }

    // 购买按钮交互（如果需要额外逻辑）
    const purchaseButtons = document.querySelectorAll('.purchase-btn');
    purchaseButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            console.log('Purchase button clicked');
            // 如果有额外逻辑，可在这里添加
        });
    });

    // 悬停提示（虽然 CSS 已经实现基本效果，以下 JS 可用于更复杂的交互）
    const purchasedItems = document.querySelectorAll('.wishlist-item.purchased');
    purchasedItems.forEach(function(item) {
        item.addEventListener('mouseenter', function() {
            let hoverInfo = item.querySelector('.hover-info');
            if (hoverInfo) {
                hoverInfo.style.display = 'block';
            }
        });
        item.addEventListener('mouseleave', function() {
            let hoverInfo = item.querySelector('.hover-info');
            if (hoverInfo) {
                hoverInfo.style.display = 'none';
            }
        });
    });
});
