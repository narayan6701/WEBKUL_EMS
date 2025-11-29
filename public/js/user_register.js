document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("profile_picture");
    const preview = document.getElementById("avatarPreview");
    const quals = document.getElementById("qualificationsList");
    const exps = document.getElementById("experiencesList");
    const addQualBtn = document.getElementById("addQualificationBtn");
    const addExpBtn = document.getElementById("addExperienceBtn");
    const resetBtn = document.getElementById("resetBtn");
    const form = document.getElementById("registerForm");

    let originalPreviewSrc = null; // Variable to hold the default image source

    // image preview (if present)
    if (input && preview) {
        originalPreviewSrc = preview.src; // Capture the default image path on page load

        input.addEventListener("change", function () {
            const file = input.files && input.files[0];
            if (!file) {
                // If user cancels file selection, revert to the original preview
                if (originalPreviewSrc) {
                    preview.src = originalPreviewSrc;
                }
                return;
            }
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    function createQualificationItem(value = "") {
        const wrapper = document.createElement("div");
        wrapper.className = "qual-item";
        const row = document.createElement("div");
        row.className = "qual-row";

        const inputEl = document.createElement("input");
        inputEl.type = "text";
        inputEl.name = "qualifications[]";
        inputEl.placeholder = "e.g. B.Sc in Computer Science";
        inputEl.required = true;
        inputEl.value = value;

        const removeBtn = document.createElement("button");
        removeBtn.type = "button";
        removeBtn.className = "remove-btn";
        removeBtn.setAttribute("aria-label", "Remove qualification");
        removeBtn.textContent = "−";

        row.appendChild(inputEl);
        row.appendChild(removeBtn);
        wrapper.appendChild(row);
        return wrapper;
    }

    function createExperienceItem(value = "") {
        const wrapper = document.createElement("div");
        wrapper.className = "exp-item";
        const row = document.createElement("div");
        row.className = "exp-row";

        const inputEl = document.createElement("input");
        inputEl.type = "text";
        inputEl.name = "experiences[]";
        inputEl.placeholder = "e.g. 3 years at Company XYZ";
        inputEl.required = true;
        inputEl.value = value;

        const removeBtn = document.createElement("button");
        removeBtn.type = "button";
        removeBtn.className = "remove-btn";
        removeBtn.setAttribute("aria-label", "Remove experience");
        removeBtn.textContent = "−";

        row.appendChild(inputEl);
        row.appendChild(removeBtn);
        wrapper.appendChild(row);
        return wrapper;
    }

    if (addQualBtn) {
        addQualBtn.addEventListener("click", function () {
            quals.appendChild(createQualificationItem());
        });
    }

    if (addExpBtn) {
        addExpBtn.addEventListener("click", function () {
            exps.appendChild(createExperienceItem());
        });
    }

    // delegate remove
    document.addEventListener("click", function (e) {
        if (e.target && e.target.classList.contains("remove-btn")) {
            const parent = e.target.closest(".qual-item, .exp-item");
            if (parent) parent.remove();
        }
    });

    // ensure reset restores single required inputs
    if (resetBtn) {
        resetBtn.addEventListener("click", function () {
            if (form) form.reset();

            quals.innerHTML =
                '<div class="qual-item"><div class="qual-row"><input type="text" name="qualifications[]" placeholder="e.g. B.Sc in Computer Science" required><button type="button" id="addQualificationBtn" class="btn-add icon-btn" aria-label="Add qualification">+</button></div></div>';
            exps.innerHTML =
                '<div class="exp-item"><div class="exp-row"><input type="text" name="experiences[]" placeholder="e.g. 3 years at Company XYZ" required><button type="button" id="addExperienceBtn" class="btn-add icon-btn" aria-label="Add experience">+</button></div></div>';

            // rebind buttons after replace
            const newAddQual = document.getElementById("addQualificationBtn");
            const newAddExp = document.getElementById("addExperienceBtn");
            if (newAddQual)
                newAddQual.addEventListener("click", function () {
                    quals.appendChild(createQualificationItem());
                });
            if (newAddExp)
                newAddExp.addEventListener("click", function () {
                    exps.appendChild(createExperienceItem());
                });

            // reset preview to original image
            if (preview && input) {
                // clear file input so the 'required' state can be re-evaluated
                try {
                    input.value = "";
                } catch (err) {}

                // Restore the original image using the path we saved on page load
                if (originalPreviewSrc) {
                    preview.src = originalPreviewSrc;
                }
            }
        });
    }
});
