<?php
$form_action = esc_url(admin_url('admin-post.php'));
$back_url = home_url('/ar/erp/');
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&family=Poppins:wght@400;600;700&display=swap"
    rel="stylesheet">

<style>
    .arg-demo-form-wrap {
        --primary: #0E9FD7;
        --primary-dark: #003148;
        --accent: #7EB049;
        --text-dark: #1a1a1a;
        --text-medium: #4a4a4a;
        --bg-white: #ffffff;
        --bg-accent: #e8f6fc;
        --border: #cce9f5;
        --error: #c41e3a;
        --success: #7EB049;
        --font-arabic: 'Cairo', sans-serif;
        font-family: var(--font-arabic);
        background: linear-gradient(135deg, var(--bg-accent) 0%, var(--bg-white) 100%);
        min-height: 100vh;
        padding: 20px;
    }

    .arg-demo-form-wrap * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .arg-demo-form-wrap .container {
        max-width: 700px;
        margin: 0 auto;
    }

    .arg-demo-form-wrap .form-container {
        background: white;
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 8px 40px rgba(14, 159, 215, 0.15);
    }

    .arg-demo-form-wrap .form-header {
        text-align: center;
        margin-bottom: 35px;
    }

    .arg-demo-form-wrap .form-title {
        font-size: 32px;
        font-weight: 900;
        color: var(--primary);
        margin-bottom: 12px;
    }

    .arg-demo-form-wrap .form-subtitle {
        font-size: 20px;
        color: var(--accent);
        font-weight: 700;
        margin-bottom: 10px;
    }

    .arg-demo-form-wrap .form-description {
        font-size: 16px;
        color: var(--text-medium);
    }

    .arg-demo-form-wrap .form-section {
        margin-bottom: 30px;
    }

    .arg-demo-form-wrap .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--border);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .arg-demo-form-wrap .section-title .badge {
        background: var(--error);
        color: white;
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 12px;
        font-weight: 600;
    }

    .arg-demo-form-wrap .section-title .badge.optional {
        background: var(--text-medium);
    }

    .arg-demo-form-wrap .form-group {
        margin-bottom: 20px;
    }

    .arg-demo-form-wrap .form-label {
        display: block;
        font-size: 15px;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .arg-demo-form-wrap .required-star {
        color: var(--error);
        margin-left: 5px;
    }

    .arg-demo-form-wrap .form-input,
    .arg-demo-form-wrap .form-select {
        width: 100%;
        padding: 14px 16px;
        font-size: 16px;
        font-family: var(--font-arabic);
        border: 2px solid var(--border);
        border-radius: 8px;
        transition: all 0.3s ease;
        background: var(--bg-white);
    }

    .arg-demo-form-wrap .form-input:focus,
    .arg-demo-form-wrap .form-select:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(14, 159, 215, 0.1);
    }

    .arg-demo-form-wrap .form-input.error {
        border-color: var(--error);
    }

    .arg-demo-form-wrap .form-input.success {
        border-color: var(--success);
    }

    .arg-demo-form-wrap .error-message {
        color: var(--error);
        font-size: 13px;
        margin-top: 6px;
        display: none;
    }

    .arg-demo-form-wrap .error-message.show {
        display: block;
    }

    .arg-demo-form-wrap .success-message {
        color: var(--success);
        font-size: 13px;
        margin-top: 6px;
        display: none;
    }

    .arg-demo-form-wrap .success-message.show {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .arg-demo-form-wrap .phone-input-wrapper {
        display: flex;
        gap: 10px;
    }

    .arg-demo-form-wrap .country-code {
        width: 100px;
        flex-shrink: 0;
    }

    .arg-demo-form-wrap .phone-input {
        margin-bottom: 0px;
        flex: 1;
    }

    .arg-demo-form-wrap .hidden {
        display: none !important;
    }

    .arg-demo-form-wrap .btn-submit {
        width: 100%;
        padding: 18px;
        font-size: 20px;
        font-weight: 700;
        font-family: var(--font-arabic);
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(14, 159, 215, 0.3);
    }

    .arg-demo-form-wrap .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(14, 159, 215, 0.4);
    }

    .arg-demo-form-wrap .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .arg-demo-form-wrap .whatsapp-section {
        text-align: center;
        margin-top: 30px;
        padding-top: 30px;
        border-top: 1px solid var(--border);
    }

    .arg-demo-form-wrap .whatsapp-title {
        font-size: 16px;
        color: var(--text-medium);
        margin-bottom: 15px;
    }

    .arg-demo-form-wrap .btn-whatsapp {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 14px 30px;
        background: #25D366;
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-size: 18px;
        font-weight: 700;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
    }

    .arg-demo-form-wrap .btn-whatsapp:hover {
        background: #20ba5a;
        transform: translateY(-2px);
    }

    .arg-demo-form-wrap .whatsapp-icon {
        font-size: 24px;
    }

    .arg-demo-form-wrap .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .arg-demo-form-wrap .checkbox-option {
        flex: 1;
        min-width: 150px;
    }

    .arg-demo-form-wrap .checkbox-option input[type="checkbox"] {
        display: none;
    }

    .arg-demo-form-wrap .checkbox-label {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px 16px;
        border: 2px solid var(--border);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        font-size: 14px;
    }

    .arg-demo-form-wrap .checkbox-option input[type="checkbox"]:checked+.checkbox-label {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    @media (max-width: 768px) {
        .arg-demo-form-wrap .form-container {
            padding: 25px;
        }

        .arg-demo-form-wrap .form-title {
            font-size: 26px;
        }

        .arg-demo-form-wrap .form-subtitle {
            font-size: 18px;
        }

        .arg-demo-form-wrap .phone-input-wrapper {
            flex-direction: column;
        }

        .arg-demo-form-wrap .country-code {
            width: 100%;
        }
    }
</style>

<div class="arg-demo-form-wrap" dir="rtl" lang="ar">
    <div
        style="background: white; padding: 15px 20px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(14, 159, 215, 0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <a href="<?php echo esc_url($back_url); ?>"
                style="text-decoration: none; color: var(--primary); font-weight: 700; font-size: 16px;">
                ← العودة للصفحة الرئيسية
            </a>
            <div>
                <svg width="120" height="40" viewBox="0 0 209 71" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M39.1565 42.8954C39.1565 42.8954 41.9105 42.0111 44.2115 43.1213C46.5125 44.2315 49.0996 47.675 50.9992 46.6856C52.8988 45.6961 53.4234 43.9549 52.9386 42.0111C52.9386 42.0111 51.9689 38.8909 51.2098 37.9132C50.4508 36.9393 45.8806 37.9132 45.8806 37.9132L39.1565 42.8954Z"
                        fill="url(#paint0_linear_141_128)" />
                    <path
                        d="M78.2891 42.1708L85.7285 25.8103H89.5436L97.0069 42.1708H92.9534L86.8492 27.7268H88.3752L82.2472 42.1708H78.2891ZM82.0088 38.665L83.0341 35.7902H91.6181L92.6672 38.665H82.0088Z"
                        fill="#FDFEFE" />
                    <defs>
                        <linearGradient id="paint0_linear_141_128" x1="52.1" y1="47.6166" x2="44.8184" y2="39.7116"
                            gradientUnits="userSpaceOnUse">
                            <stop stop-color="#0E9FD7" />
                            <stop offset="1" stop-color="#003148" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h1 class="form-title">احجز عرضك التوضيحي المجاني</h1>
                <p class="form-subtitle">✨ املأ خانتين فقط ونرتّب لك عرضًا توضيحيًا خلال 24 ساعة ✨</p>
                <p class="form-description">نساعدك في اختيار الحل المناسب لأعمالك</p>
            </div>

            <form id="demoForm" action="<?php echo $form_action; ?>" method="POST">
                <input type="hidden" name="action" value="arg_demo_form">
                <?php wp_nonce_field('arg_demo_form', 'arg_form_nonce'); ?>

                <div class="form-section">
                    <h2 class="section-title">
                        <span>البيانات الأساسية</span>
                        <span class="badge">مطلوب</span>
                    </h2>

                    <div class="form-group">
                        <label class="form-label" for="name">
                            الاسم الكامل
                            <span class="required-star">*</span>
                        </label>
                        <input type="text" id="name" name="name" class="form-input" required
                            placeholder="مثال: أحمد محمد العمري">
                        <div class="error-message" id="name-error">يرجى إدخال الاسم</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="contact-time">
                            أفضل وقت للتواصل
                            <span class="required-star">*</span>
                        </label>
                        <select id="contact-time" name="contact_time" class="form-select" required>
                            <option value="">اختر الوقت المناسب</option>
                            <option value="morning">صباحاً (9 ص - 12 م)</option>
                            <option value="afternoon">ظهراً (12 م - 3 م)</option>
                            <option value="evening">مساءً (3 م - 6 م)</option>
                            <option value="anytime">أي وقت</option>
                        </select>
                        <div class="error-message" id="contact-time-error">يرجى اختيار الوقت</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="communication-method">
                            طريقة التواصل المفضلة
                            <span class="required-star">*</span>
                        </label>
                        <select id="communication-method" name="communication_method" class="form-select" required>
                            <option value="">اختر طريقة التواصل</option>
                            <option value="call">مكالمة هاتفية</option>
                            <option value="whatsapp">واتساب</option>
                            <option value="email">بريد إلكتروني</option>
                        </select>
                        <div class="error-message" id="communication-method-error">يرجى اختيار طريقة التواصل</div>
                    </div>

                    <div class="form-group" id="phone-group">
                        <label class="form-label" for="phone">
                            رقم الجوال
                            <span class="required-star">*</span>
                        </label>
                        <div class="phone-input-wrapper">
                            <select class="form-select country-code" id="country-code" name="country_code">
                                <option value="+966" selected>🇸🇦 +966</option>
                                <option value="+971">🇦🇪 +971</option>
                                <option value="+965">🇰🇼 +965</option>
                                <option value="+973">🇧🇭 +973</option>
                                <option value="+974">🇶🇦 +974</option>
                            </select>
                            <input type="tel" id="phone" name="phone" class="form-input phone-input"
                                placeholder="5X XXX XXXX" pattern="[0-9]{9,10}" required>
                        </div>
                        <div class="error-message" id="phone-error">يرجى إدخال رقم جوال صحيح (9-10 أرقام)</div>
                        <div class="success-message" id="phone-success">✓ رقم صحيح</div>
                    </div>

                    <div class="form-group hidden" id="email-group">
                        <label class="form-label" for="email">
                            البريد الإلكتروني
                            <span class="required-star">*</span>
                        </label>
                        <input type="email" id="email" name="email" class="form-input"
                            placeholder="example@company.com">
                        <div class="error-message" id="email-error">يرجى إدخال بريد إلكتروني صحيح</div>
                        <div class="success-message" id="email-success">✓ بريد إلكتروني صحيح</div>
                    </div>
                </div>

                <div class="form-section">
                    <h2 class="section-title">
                        <span>معلومات إضافية</span>
                        <span class="badge optional">اختياري</span>
                    </h2>

                    <div class="form-group">
                        <label class="form-label" for="company">اسم الشركة</label>
                        <input type="text" id="company" name="company" class="form-input"
                            placeholder="سيتم ملؤها تلقائياً من البريد الإلكتروني">
                    </div>

                    <div class="form-group" id="secondary-phone-group">
                        <label class="form-label" for="secondary-phone">رقم جوال إضافي</label>
                        <div class="phone-input-wrapper">
                            <select class="form-select country-code" name="secondary_country_code">
                                <option value="+966" selected>🇸🇦 +966</option>
                                <option value="+971">🇦🇪 +971</option>
                            </select>
                            <input type="tel" id="secondary-phone" name="secondary_phone" class="form-input phone-input"
                                placeholder="5X XXX XXXX">
                        </div>
                    </div>

                    <div class="form-group hidden" id="secondary-email-group">
                        <label class="form-label" for="secondary-email">بريد إلكتروني إضافي</label>
                        <input type="email" id="secondary-email" name="secondary_email" class="form-input"
                            placeholder="example@company.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="startup">هل أنت شركة ناشئة؟</label>
                        <select id="startup" name="startup" class="form-select">
                            <option value="">اختر</option>
                            <option value="yes">نعم</option>
                            <option value="no">لا</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">الأولوية في الاختيار (يمكن اختيار أكثر من واحد)</label>
                        <div class="checkbox-group">
                            <div class="checkbox-option">
                                <input type="checkbox" id="priority-cost" name="priority[]" value="cost">
                                <label for="priority-cost" class="checkbox-label">💰 التكلفة</label>
                            </div>
                            <div class="checkbox-option">
                                <input type="checkbox" id="priority-zatca" name="priority[]" value="zatca">
                                <label for="priority-zatca" class="checkbox-label">✅ التكامل مع زاتكا</label>
                            </div>
                            <div class="checkbox-option">
                                <input type="checkbox" id="priority-modules" name="priority[]" value="modules">
                                <label for="priority-modules" class="checkbox-label">📦 تنوع الوحدات</label>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="submit-btn">
                    احجز العرض التوضيحي الآن
                </button>
            </form>

            <div class="whatsapp-section">
                <p class="whatsapp-title">أو تواصل معنا مباشرة عبر واتساب للاستفسارات السريعة</p>
                <a href="https://wa.me/966534470843?text=مرحباً، أرغب في معرفة المزيد عن نظام Odoo ERP"
                    class="btn-whatsapp" target="_blank">
                    <span class="whatsapp-icon">💬</span>
                    تواصل عبر واتساب
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var form = document.getElementById('demoForm');
        var communicationMethod = document.getElementById('communication-method');
        var phoneGroup = document.getElementById('phone-group');
        var emailGroup = document.getElementById('email-group');
        var secondaryPhoneGroup = document.getElementById('secondary-phone-group');
        var secondaryEmailGroup = document.getElementById('secondary-email-group');
        var phoneInput = document.getElementById('phone');
        var emailInput = document.getElementById('email');
        var companyInput = document.getElementById('company');

        communicationMethod.addEventListener('change', function () {
            var method = this.value;
            if (method === 'email') {
                phoneGroup.classList.add('hidden');
                emailGroup.classList.remove('hidden');
                phoneInput.removeAttribute('required');
                emailInput.setAttribute('required', 'required');
                secondaryPhoneGroup.classList.remove('hidden');
                secondaryEmailGroup.classList.add('hidden');
            } else {
                emailGroup.classList.add('hidden');
                phoneGroup.classList.remove('hidden');
                emailInput.removeAttribute('required');
                phoneInput.setAttribute('required', 'required');
                secondaryPhoneGroup.classList.add('hidden');
                secondaryEmailGroup.classList.remove('hidden');
            }
        });

        phoneInput.addEventListener('input', function () {
            var value = this.value.replace(/\D/g, '');
            var errorMsg = document.getElementById('phone-error');
            var successMsg = document.getElementById('phone-success');
            if (value.length >= 9 && value.length <= 10) {
                this.classList.remove('error');
                this.classList.add('success');
                errorMsg.classList.remove('show');
                successMsg.classList.add('show');
            } else if (value.length > 0) {
                this.classList.add('error');
                this.classList.remove('success');
                errorMsg.classList.add('show');
                successMsg.classList.remove('show');
            } else {
                this.classList.remove('error', 'success');
                errorMsg.classList.remove('show');
                successMsg.classList.remove('show');
            }
            this.value = value;
        });

        emailInput.addEventListener('input', function () {
            var value = this.value;
            var errorMsg = document.getElementById('email-error');
            var successMsg = document.getElementById('email-success');
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailRegex.test(value)) {
                this.classList.remove('error');
                this.classList.add('success');
                errorMsg.classList.remove('show');
                successMsg.classList.add('show');
                var domain = value.split('@')[1];
                if (domain && !companyInput.value) {
                    var companyName = domain.split('.')[0];
                    companyInput.value = companyName.charAt(0).toUpperCase() + companyName.slice(1);
                }
            } else if (value.length > 0) {
                this.classList.add('error');
                this.classList.remove('success');
                errorMsg.classList.add('show');
                successMsg.classList.remove('show');
            } else {
                this.classList.remove('error', 'success');
                errorMsg.classList.remove('show');
                successMsg.classList.remove('show');
            }
        });

        form.addEventListener('submit', function () {
            document.getElementById('submit-btn').disabled = true;
            document.getElementById('submit-btn').textContent = 'جاري الإرسال...';
        });
    })();
</script>