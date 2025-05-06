  <style>
    .main-box {
      max-width: 800px;
      margin: 40px auto 60px;
      background-color: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }
    .step-title {
      font-weight: 600;
      margin-top: 20px;
      margin-bottom: 10px;
    }
    .screenshot-placeholder {
      border: 2px dashed #ccc;
      background-color: #f0f0f0;
      text-align: center;
      color: #777;
      padding: 20px;
      border-radius: 5px;
      margin-top: 10px;
    }
    .screenshot-img {
      width: 100%;
      max-width: 100%;
      height: auto;
      max-height: 400px;
      object-fit: contain;
      border: 1px solid #ddd;
      border-radius: 5px;
      margin-top: 10px;
    }
  </style>
  <div class="container">
    <div class="main-box">
      <h1 class="text-center mb-4">Welcome to RIE </h1>

      <div class="step">
        <div class="step-title">Step 1:</div>
        <p>For registration, click the link below the login form. After this, you have to enter your email and then click the <strong>Send Email</strong> button. You will receive the OTP in your email.</p>
        <div class="screenshot-placeholder">
          <img src="<?= base_url('\public\assets\img\instruction-images\RIE_1.jpg') ?>" alt="Step 2 Screenshot" class="screenshot-img">
        </div>
      </div>
      
      <div class="step">
        <div class="step-title">Step 2:</div>
        <p>Then enter the OTP in the input field and click on the <strong>Verify</strong> button.</p>
        <div class="screenshot-placeholder">
          <img src="<?= base_url('\public\assets\img\instruction-images\RIE_2.jpg') ?>" alt="Step 2 Screenshot" class="screenshot-img">
        </div>
      </div>
      <div class="step">
        <div class="step-title">Step 3:</div>
        <p> The registration form will open like this:</p>
        <p>After Submit you will redirect to the <strong>Login Form</strong></p>
        <div class="screenshot-placeholder">
          <img src="<?= base_url('\public\assets\img\instruction-images\RIE_login.jpg') ?>" alt="Step 2 Screenshot" class="screenshot-img">
        </div>
      </div>
      <div class="step">
        <div class="step-title">Step 4:</div>
          <p>When you login at first time you will see this acknowledgment box and this will not close until you have checked the checkbox I have read and agreed with the guidelines and policies mentioned above </p>
          <p>Once you have checked the checkbox the <strong>Save & Continue</strong> button in enabled and then you login successfully</p>
          <div class="screenshot-placeholder">
            <img src="<?= base_url('\public\assets\img\instruction-images\RIE_Guideline.jpg') ?>" alt="Step 2 Screenshot" class="screenshot-img">
        </div>
      </div>
      <div class="step">
        <div class="step-title">Step 5:</div>
          <p>Academic Detail Page is Appear with Fill details on top</p>
          <div class="screenshot-placeholder">
            <img src="<?= base_url('\public\assets\img\instruction-images\RIE_Academic.png') ?>" alt="Step 2 Screenshot" class="screenshot-img">
          </div>
          <p>Then you have to fill the rest details once you have done submit the form the <strong>Print Academic Detail</strong>page is open</p>
          <div class="screenshot-placeholder">
            <img src="<?= base_url('\public\assets\img\instruction-images\RIE_Print_Academic_Detail.jpg') ?>" alt="Step 2 Screenshot" class="screenshot-img">
          </div>
          <p>There is a print button on the top right corner you can download the academic detail in pdf format on click of that button</p>
      </div>

      <div class="step">
        <div class="step-title">Step 6:</div>
          <p>Pay Form Fees</p>
          <div class="screenshot-placeholder">
            <img src="<?= base_url('\public\assets\img\instruction-images\RIE_Pay_Form_Fees.jpg') ?>" alt="Step 2 Screenshot" class="screenshot-img">
          </div>
          <p>You have to make payment on the account ID given below and fill the receipt number in the field and after the payment is successful, attach its screenshot and submit it.</p>
    </div>
  </div>
