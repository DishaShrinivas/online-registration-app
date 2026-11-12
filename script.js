$(function () {
    const $form = $("#registrationForm");
    if (!$form.length) return;

    function showError($field, message) {
        $field.closest(".field").addClass("invalid").find(".field-error").text(message);
    }

    function clearError($field) {
        $field.closest(".field").removeClass("invalid").find(".field-error").text("");
    }

    function validateForm() {
        let valid = true;
        const name = $("#full_name");
        const email = $("#email");
        const phone = $("#phone");
        const dob = $("#dob");
        const gender = $("#gender");
        const course = $("#course");
        const address = $("#address");

        [name, email, phone, dob, gender, course, address].forEach(clearError);

        if (!name.val().trim()) { showError(name, "Please enter your full name."); valid = false; }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.val().trim())) {
            showError(email, "Please enter a valid email address."); valid = false;
        }
        if (!/^[0-9+\-\s()]{7,20}$/.test(phone.val().trim())) {
            showError(phone, "Please enter a valid phone number."); valid = false;
        }
        if (!dob.val() || new Date(dob.val() + "T00:00:00") > new Date(new Date().toDateString())) {
            showError(dob, "Please select a valid date of birth."); valid = false;
        }
        if (!gender.val()) { showError(gender, "Please select an option."); valid = false; }
        if (!course.val()) { showError(course, "Please select a course."); valid = false; }
        if (!address.val().trim()) { showError(address, "Please enter your address."); valid = false; }

        if (!$("#terms").is(":checked")) {
            $(".terms-error").text("Please confirm that the information is correct.");
            valid = false;
        } else {
            $(".terms-error").text("");
        }
        return valid;
    }

    $form.on("submit", function (event) {
        if (!validateForm()) {
            event.preventDefault();
            const $firstError = $(".field.invalid").first();
            if ($firstError.length) $firstError.find("input, select, textarea").first().trigger("focus");
        }
    });

    $form.find("input, select, textarea").on("input change", function () {
        clearError($(this));
        if (this.id === "terms" && this.checked) $(".terms-error").text("");
    });
});
