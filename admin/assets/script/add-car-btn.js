// Modal functionality
const modal = document.getElementById("carModal");
const openBtn = document.getElementById("openModalBtn");
const closeBtn = document.getElementById("closeModalBtn");
const closeBtn2 = document.getElementById("closeModalBtn2");

openBtn.onclick = () => modal.style.display = "flex";
closeBtn.onclick = () => modal.style.display = "none";
closeBtn2.onclick = () => modal.style.display = "none";

// Close if clicked outside modal content
window.onclick = (e) => {
    if (e.target === modal) {
        modal.style.display = "none";
    }
};