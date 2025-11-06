document.addEventListener("DOMContentLoaded", () => {
    let isPinned = false;


    const drawerCheckbox = document.getElementById("my-drawer-4");
    const drawerSide = document.querySelector(".drawer-side");
    const pinToggle = document.getElementById("sidebar-pin-toggle"); 


    if (drawerCheckbox && drawerSide && pinToggle) {
        pinToggle.addEventListener("click", (e) => {
            e.preventDefault();
            isPinned = !isPinned;
            drawerCheckbox.checked = isPinned;
        });

        drawerSide.addEventListener("mouseenter", () => {
            drawerCheckbox.checked = true;
        });

        drawerSide.addEventListener("mouseleave", () => {
            if (!isPinned) {
                drawerCheckbox.checked = false;
            }
        });
    }
});
