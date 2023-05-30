let toastContainer = null;

function showToast(message, type) {
    if (!toastContainer) {
        toastContainer = document.createElement("div");
        toastContainer.className = "toast-container";
        document.body.appendChild(toastContainer);
    }

    // If there's already a toast, remove it
    if (toastContainer.firstChild) {
        toastContainer.firstChild.remove();
    }

    let toast = document.createElement("div");
    toast.className = "toast toast-" + type;
    toast.textContent = message;

    toastContainer.appendChild(toast);
    // trigger de la transition
    setTimeout(() => toast.classList.add("show"), 0);

    // 5 sec
    setTimeout(() => {
        toast.classList.remove("show");
        // Retirer de la DOM
        toast.addEventListener("transitionend", () => toast.remove());
    }, 5000);
}
