let toastContainer = null;

function showToast(message, type) {
    if (!toastContainer) {
        toastContainer = document.createElement("div");
        toastContainer.className = "toast-container";
        document.body.appendChild(toastContainer);
    }

    let toast = document.createElement("div");
    toast.className = "toast toast-" + type;
    toast.textContent = message;

    toastContainer.appendChild(toast);
    setTimeout(function () {
        toast.className += " show";
        setTimeout(function () {
            toast.className = toast.className.replace(" show", "");
            setTimeout(function () {
                toastContainer.removeChild(toast);
                if (!toastContainer.firstChild) {
                    document.body.removeChild(toastContainer);
                    toastContainer = null;
                }
            }, 500);
        }, 5000);
    }, 100);
}

