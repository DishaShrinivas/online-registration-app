
import streamlit as st
import re
from datetime import datetime, date

# Page configuration
st.set_page_config(
    page_title="Online Application Registration",
    page_icon="🎓",
    layout="centered"
)

# CSS styling
st.markdown("""
<style>
.stApp {
    background-color: #f5f6fa;
}

.main-title {
    text-align: center;
    color: #263b70;
    font-size: 34px;
    font-weight: bold;
}

.subtitle {
    text-align: center;
    color: #64748b;
}

div.stButton > button,
div.stFormSubmitButton > button {
    background-color: #3155a4;
    color: white;
    border-radius: 8px;
    width: 100%;
    height: 45px;
    font-weight: bold;
}

.success-title {
    color: #16834a;
    font-size: 28px;
    font-weight: bold;
}
</style>
""", unsafe_allow_html=True)


# Header
st.markdown(
    '<p class="main-title">🎓 Application Registration</p>',
    unsafe_allow_html=True
)

st.markdown(
    '<p class="subtitle">ONLINE PORTAL</p>',
    unsafe_allow_html=True
)

st.markdown(
    '<p class="subtitle">Complete the form below to submit your application.</p>',
    unsafe_allow_html=True
)


# Course and gender options
gender_options = [
    "Female",
    "Male",
    "Other",
    "Prefer not to say"
]

course_options = [
    "Computer Science",
    "Information Science",
    "Electronics",
    "Mechanical",
    "Civil",
    "Other"
]


# Store registration details
if "submitted_data" not in st.session_state:
    st.session_state.submitted_data = None


# SUCCESS PAGE
if st.session_state.submitted_data:

    data = st.session_state.submitted_data

    st.success("✓ SUBMISSION SUCCESSFUL")

    st.markdown(
        '<p class="success-title">Thank you, '
        + data["full_name"] + '!</p>',
        unsafe_allow_html=True
    )

    st.write(
        "Your application information has been received "
        "and is displayed below."
    )

    st.subheader("Application Details")

    st.write("**Full name:**", data["full_name"])
    st.write("**Email address:**", data["email"])
    st.write("**Phone number:**", data["phone"])
    st.write("**Date of birth:**", data["dob"])
    st.write("**Gender:**", data["gender"])
    st.write("**Selected course:**", data["course"])
    st.write("**Address:**", data["address"])

    st.markdown("---")

    st.info("Your application has been submitted successfully.")

    if st.button("Submit Another Application"):
        st.session_state.submitted_data = None
        st.rerun()


# REGISTRATION FORM
else:

    st.subheader("APPLICATION FORM")
    st.markdown("### Personal Information")
    st.caption("* Required fields")

    with st.form("registration_form"):

        full_name = st.text_input(
            "Full name *",
            max_chars=100,
            placeholder="Enter your full name"
        )

        email = st.text_input(
            "Email address *",
            placeholder="name@example.com"
        )

        phone = st.text_input(
            "Phone number *",
            max_chars=20,
            placeholder="+91 98765 43210"
        )

        dob = st.text_input(
            "Date of birth *",
            placeholder="YYYY-MM-DD"
        )

        gender = st.selectbox(
            "Gender *",
            ["Select an option"] + gender_options
        )

        course = st.selectbox(
            "Course applying for *",
            ["Select a course"] + course_options
        )

        address = st.text_area(
            "Address *",
            max_chars=500,
            placeholder="Enter your complete address"
        )

        terms = st.checkbox(
            "I confirm that the information provided above is correct. *"
        )

        st.caption(
            "Demo application form. Information is displayed "
            "after successful validation."
        )

        submit = st.form_submit_button(
            "Submit Application →"
        )


    # FORM VALIDATION
    if submit:

        errors = []

        # Name validation
        if not full_name.strip():
            errors.append("Full name is required.")

        elif len(full_name.strip()) > 100:
            errors.append(
                "Full name must be 100 characters or fewer."
            )

        # Email validation
        if not re.fullmatch(
            r"[^@\s]+@[^@\s]+\.[^@\s]+",
            email.strip()
        ):
            errors.append("Enter a valid email address.")

        # Phone validation
        if not re.fullmatch(
            r"[0-9+\-\s()]{7,20}",
            phone.strip()
        ):
            errors.append(
                "Enter a valid phone number (7–20 characters)."
            )

        # Date of birth validation
        try:
            birth_date = datetime.strptime(
                dob.strip(), "%Y-%m-%d"
            ).date()

            if birth_date > date.today():
                errors.append(
                    "Enter a date of birth that is not in the future."
                )

        except ValueError:
            errors.append(
                "Enter a valid date of birth in YYYY-MM-DD format."
            )

        # Gender validation
        if gender not in gender_options:
            errors.append("Please select a gender option.")

        # Course validation
        if course not in course_options:
            errors.append("Please select a course.")

        # Address validation
        if not address.strip():
            errors.append("Address is required.")

        elif len(address.strip()) > 500:
            errors.append(
                "Address must be 500 characters or fewer."
            )

        # Declaration validation
        if not terms:
            errors.append(
                "You must confirm that the information is correct."
            )

        # Display errors or success
        if errors:

            st.error("Please correct the following:")

            for error in errors:
                st.write("❌", error)

        else:

            st.session_state.submitted_data = {
                "full_name": full_name.strip(),
                "email": email.strip(),
                "phone": phone.strip(),
                "dob": dob.strip(),
                "gender": gender,
                "course": course,
                "address": address.strip()
            }

            st.rerun()


# Footer
st.markdown("---")

st.caption(
    "Online Application Portal · "
    "Built with Python and Streamlit"
)
