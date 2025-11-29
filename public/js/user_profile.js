document.addEventListener("DOMContentLoaded", function () {
    function setupDynamicList(
        addButtonId,
        listContainerId,
        inputName,
        placeholder
    ) {
        const addBtn = document.getElementById(addButtonId);
        const listContainer = document.getElementById(listContainerId);
        addBtn.addEventListener("click", () => {
            const newItem = document.createElement("div");
            newItem.className = "list-item";
            newItem.innerHTML = `<input type="text" name="${inputName}[]" placeholder="${placeholder}" required><button type="button" class="btn-remove">−</button>`;
            listContainer.appendChild(newItem);
        });
        listContainer.addEventListener("click", (e) => {
            if (e.target.classList.contains("btn-remove")) {
                if (listContainer.querySelectorAll(".list-item").length > 1) {
                    e.target.closest(".list-item").remove();
                }
            }
        });
    }
    setupDynamicList(
        "addQualificationBtn",
        "qualificationsList",
        "qualifications",
        "e.g. B.Sc in CompSci"
    );
    setupDynamicList(
        "addExperienceBtn",
        "experiencesList",
        "experiences",
        "e.g. 3 years at Acme Inc"
    );

    const avatarInput = document.getElementById("profile_picture");
    const avatarPreview = document.getElementById("avatarPreview");
    avatarInput.addEventListener("change", function (event) {
        const file = event.target.files[0];
        if (file) {
            avatarPreview.src = URL.createObjectURL(file);
        }
    });

    const form = document.getElementById("profileForm");
    if (!form) return;

    // create / reuse a status container
    let respContainer = document.getElementById("ajaxResponse");
    if (!respContainer) {
        respContainer = document.createElement("div");
        respContainer.id = "ajaxResponse";
        respContainer.style.marginBottom = "1.5rem";
        form.prepend(respContainer);
    }

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        respContainer.style.color = "black";
        respContainer.textContent = "Saving...";

        try {
            const formData = new FormData(form);
            if (!formData.has("_method")) formData.set("_method", "PATCH");

            const meta = document.querySelector('meta[name="csrf-token"]');
            const csrf = meta
                ? meta.getAttribute("content")
                : formData.get("_token") || "";

            const res = await fetch(form.action, {
                method: "POST",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": csrf || "",
                    Accept: "application/json",
                },
                body: formData,
                credentials: "same-origin",
            });

            // parse json if possible
            let json = {};
            try {
                json = await res.json();
            } catch (err) {
                /* ignore */
            }

            if (res.ok) {
                // show whatever message the server returned (including "No changes to save")
                respContainer.style.color =
                    json.message &&
                    json.message.toLowerCase().includes("no changes")
                        ? "gray"
                        : "green";
                respContainer.textContent =
                    json.message || "Profile updated successfully";
                // update avatar preview if provided
                if (json.profile_picture_url) {
                    const img = document.getElementById("avatarPreview");
                    if (img) img.src = json.profile_picture_url;
                }
                return;
            }

            if (res.status === 422 && json.errors) {
                respContainer.style.color = "crimson";
                respContainer.innerHTML = Object.values(json.errors)
                    .flat()
                    .map((s) => `<p>${s}</p>`)
                    .join("");
                return;
            }

            // other error
            respContainer.style.color = "crimson";
            respContainer.textContent =
                json.message || `Error: ${res.status} ${res.statusText}`;
        } catch (err) {
            respContainer.style.color = "crimson";
            respContainer.textContent = "Network error (see console)";
            console.error(err);
        }
    });
});
