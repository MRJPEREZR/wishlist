/* purchase.js */

document.addEventListener('DOMContentLoaded', function() {
    const purchaseForm = document.getElementById('purchase-form');

    if (purchaseForm) {
        purchaseForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // 简单前端验证：确保文件已经选择
            const fileInput = document.getElementById('proof');
            if (fileInput.files.length === 0) {
                alert("请上传购买凭证！");
                return;
            }
            
            console.log('提交表单...');
            
            // 使用 FormData 收集表单数据
            const formData = new FormData(purchaseForm);
            
            // 这里使用 fetch 模拟 AJAX 提交，实际项目中请确认后端接口支持 AJAX 提交
            fetch(purchaseForm.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessModal();
                } else {
                    alert("提交失败，请重试。");
                }
            })
            .catch(error => {
                console.error('提交错误:', error);
                alert("提交过程中发生错误！");
            });
        });
    }

    // 显示成功弹窗
    function showSuccessModal() {
        let modal = document.getElementById('purchase-success-modal');
        if (!modal) {
            // 如果不存在则创建弹窗元素
            modal = document.createElement('div');
            modal.id = 'purchase-success-modal';
            modal.className = 'modal';
            modal.innerHTML = `
                <div class="modal-content">
                    <p>恭喜！购买凭证上传成功。</p>
                    <button class="btn" id="confirm-modal-btn">确认</button>
                </div>
            `;
            document.body.appendChild(modal);
        }
        modal.style.display = 'flex';

        document.getElementById('confirm-modal-btn').addEventListener('click', function() {
            // 点击确认后跳转到共享愿望清单页面，返回 URL 由后端传入或在隐藏域中提供
            const returnUrl = document.getElementById('return-url').value;
            window.location.href = returnUrl;
        });
    }
});
