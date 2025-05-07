// /lms-frontend/public/js/formValidator.js
class FormValidator {
    constructor(form) {
      this.form   = form;
      this.fields = {
        first_name: {
          required: true,
          message: "First name is required."
        },
        last_name: {
          required: true,
          message: "Last name is required."
        },
        email: {
          required: true,
          pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
          message: "Enter a valid email address."
        },
        phone_number: {
          required: false,
          pattern: /^[0-9]{10,15}$/,
          message: "Enter a valid phone number (10-15 digits)."
        },
        password: {
          required: true,
          minLength: 8,
          message: "Password must be at least 8 characters long."
        },
        password_confirmation: {
          required: true,
          match: "password",
          message: "Passwords do not match."
        }
      };
      this.attachListeners();
      this.attachSubmitHandler();
    }
  
    attachListeners() {
      Object.keys(this.fields).forEach(name => {
        const input = this.form.querySelector(`[name="${name}"]`);
        if (input) {
          input.addEventListener('input', () => this.validateField(name));
          input.addEventListener('blur',  () => this.validateField(name));
        }
      });
    }
  
    attachSubmitHandler() {
      this.form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Validate all fields
        let isValid = true;
        Object.keys(this.fields).forEach(name => {
          if (!this.validateField(name)) {
            isValid = false;
          }
        });
  
        if (!isValid) {
          this.showFormError("Please correct the errors in the form before submitting.");
          return;
        }
  
        try {
          const formData = new FormData(this.form);
          const response = await fetch(this.form.action, {
            method: 'POST',
            body: formData
          });
  
          if (!response.ok) {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
              const data = await response.json();
              throw new Error(data.message || 'Registration failed');
            } else {
              throw new Error(`Server error: ${response.status}`);
            }
          }
  
          const data = await response.json();
          if (data.success) {
            window.location.href = '/lms-frontend/public/auth/login';
          } else {
            throw new Error(data.message || 'Registration failed');
          }
        } catch (error) {
          this.showFormError(this.formatErrorMessage(error.message));
        }
      });
    }
  
    formatErrorMessage(error) {
      // Format common error messages to be more user-friendly
      const errorMap = {
        '404': 'The registration service is currently unavailable. Please try again later.',
        '500': 'An unexpected error occurred. Please try again later.',
        'NetworkError': 'Unable to connect to the server. Please check your internet connection.',
        'Failed to fetch': 'Unable to connect to the server. Please check your internet connection.'
      };
  
      // Check for specific error patterns
      for (const [key, message] of Object.entries(errorMap)) {
        if (error.includes(key)) {
          return message;
        }
      }
  
      // If no specific pattern matches, return a generic message
      return 'Registration failed. Please try again later.';
    }
  
    showFormError(message) {
      let alertDiv = this.form.querySelector('.alert-danger');
      if (!alertDiv) {
        alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger mt-3';
        this.form.insertBefore(alertDiv, this.form.firstChild);
      }
      alertDiv.textContent = message;
      alertDiv.style.display = 'block';
    }
  
    validateField(name) {
      const input = this.form.querySelector(`[name="${name}"]`);
      const field = this.fields[name];
      let valid = true, error = "";
  
      if (field.required && !input.value.trim()) {
        valid = false; error = field.message;
      } else if (field.pattern && input.value && !field.pattern.test(input.value)) {
        valid = false; error = field.message;
      } else if (field.minLength && input.value.length < field.minLength) {
        valid = false; error = field.message;
      } else if (field.match) {
        const matchInput = this.form.querySelector(`[name="${field.match}"]`);
        if (input.value !== matchInput.value) {
          valid = false; error = field.message;
        }
      }
  
      this.setFieldState(input, valid, error);
      return valid;
    }
  
    setFieldState(input, valid, error) {
      const mb3      = input.closest('.mb-3');
      const formText = mb3.querySelector('.form-text');
      const errorElem= mb3.querySelector('.invalid-feedback');
  
      input.classList.toggle('is-invalid', !valid);
      input.setAttribute('aria-invalid', !valid);
  
      if (!valid) {
        errorElem.textContent = error;
        errorElem.classList.add('active');
        formText?.classList.add('hidden');
      } else {
        errorElem.textContent = "";
        errorElem.classList.remove('active');
        formText?.classList.remove('hidden');
      }
    }
  }
  
  document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registrationForm');
    if (form) new FormValidator(form);
  });
  