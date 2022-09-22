<!DOCTYPE html>
<html lang="en">
  <head>
	 <title> Спасибо, что Вы заботитесь о своем здоровье и воспользовались
                  сервисом рекомендаций в центре "Мои документы".</title>
     <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
     <meta http-equiv="X-UA-Compatible" content="IE=edge" />
     <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
	<?/*?>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <?*/?>
    <style>
      /* global */
      *,
      *::before,
      *::after {
        box-sizing: border-box;
      }

      h6,
      h5,
      h4,
      h3,
      h2,
      h1 {
        margin-top: 0;
        margin-bottom: 0.5rem;
      }

      p {
        margin-top: 0;
        margin-bottom: 1rem;
      }

      table {
        caption-side: bottom;
        border-collapse: collapse;
      }

      body {
        margin: 0;
        font-family: "Inter", sans-serif;
      }
      /* End global */

      /* bootstrap */
      .container-fluid {
        width: 100%;
        padding-right: 1.21875rem;
        padding-left: 1.21875rem;
        margin-right: auto;
        margin-left: auto;
      }

      .row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -1.21875rem;
        margin-left: -1.21875rem;
      }

      .row > * {
        box-sizing: border-box;
        flex-shrink: 0;
        width: 100%;
        max-width: 100%;
        padding-right: 1.21875rem;
        padding-left: 1.21875rem;
      }

      .col {
        flex: 1 0 0%;
      }

      .col-1 {
        flex: 0 0 auto;
        width: 8.33333333%;
      }

      .col-2 {
        flex: 0 0 auto;
        width: 16.66666667%;
      }

      .col-3 {
        flex: 0 0 auto;
        width: 25%;
      }

      .col-4 {
        flex: 0 0 auto;
        width: 33.33333333%;
      }

      .col-5 {
        flex: 0 0 auto;
        width: 41.66666667%;
      }

      .col-6 {
        flex: 0 0 auto;
        width: 50%;
      }

      .col-7 {
        flex: 0 0 auto;
        width: 58.33333333%;
      }

      .col-8 {
        flex: 0 0 auto;
        width: 66.66666667%;
      }

      .col-9 {
        flex: 0 0 auto;
        width: 75%;
      }

      .col-10 {
        flex: 0 0 auto;
        width: 83.33333333%;
      }

      .col-11 {
        flex: 0 0 auto;
        width: 91.66666667%;
      }

      .col-12 {
        flex: 0 0 auto;
        width: 100%;
      }

      .breadcrumb {
        display: flex;
        flex-wrap: wrap;
        padding: 0 0;
        margin-bottom: 1rem;
        list-style: none;
      }

      .breadcrumb-item + .breadcrumb-item {
        padding-left: 0.5rem;
      }
      .breadcrumb-item + .breadcrumb-item::before {
        float: left;
        padding-right: 0.5rem;
        content: "|";
        color: #c9c9c9;
      }

      .mb-1 {
        margin-bottom: 0.25rem !important;
      }

      .mb-2 {
        margin-bottom: 0.5rem !important;
      }

      .mb-3 {
        margin-bottom: 1rem !important;
      }

      .mb-4 {
        margin-bottom: 1.5rem !important;
      }

      .mb-5 {
        margin-bottom: 3rem !important;
      }

      .progress {
        display: flex;
        height: 1rem;
        overflow: hidden;
        font-size: 0.75rem;
        background-color: #e9ecef;
        border-radius: 0.25rem;
      }

      .progress-bar {
        display: flex;
        flex-direction: column;
        justify-content: center;
        overflow: hidden;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        background-color: #0d6efd;
        transition: width 0.6s ease;
      }

      .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
        vertical-align: top;
        border-color: #dee2e6;
      }
      .table > :not(caption) > * > * {
        padding: 0.5rem 0.5rem;
        border-bottom-width: 1px;
      }
      .table > tbody {
        vertical-align: inherit;
      }
      .table > thead {
        vertical-align: bottom;
      }
      .table > :not(:first-child) {
        border-top: 2px solid currentColor;
      }
      .table-borderless > :not(caption) > * > * {
        border-bottom-width: 0;
      }
      .table-borderless > :not(:first-child) {
        border-top-width: 0;
      }
      /* End bootstrap */

      /* header */
      .header {
        padding: 74px 0;
      }

      .header__brand {
        display: flex;
        align-items: center;
        text-decoration: none;
      }

      .header__logo {
        margin-right: 10px;
      }

      .header__title {
        font-weight: bold;
        font-size: 26.0699px;
        line-height: 31px;
        letter-spacing: 0.521397px;
        color: #7db360;
        margin-bottom: 0;
      }
      /* End header */

      /* page */
      .page {
        background: #ffffff;
      }

      .page__container {
        max-width: 600px;
      }

      .page__content {
      }

      .page__title {
        font-weight: 600;
        font-size: 18px;
        line-height: 30px;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        color: #2c2a29;
        margin: 0;
      }

      .page__title span {
        color: #e04e39;
      }

      .breadcrumb-block {
      }

      .breadcrumb-block__list {
        margin: 0;
      }

      .breadcrumb-block__item {
        font-weight: 600;
        font-size: 18px;
        line-height: 23px;
        letter-spacing: 0.4px;
        color: #000000;
      }

      .breadcrumb-block__item_red {
        color: #e04e39;
      }
      .breadcrumb-block__item_yellow {
        color: #ffb931;
      }
      .breadcrumb-block__item_green {
        color: #7db360;
      }

      .page__prompt {
        font-weight: 500;
        font-size: 12px;
        line-height: 23px;
        letter-spacing: 0.4px;
        color: #000000;
        opacity: 0.6;
        margin: 0;
      }
      /* End page */

      /* info-block */
      .info-block {
      }

      .info-block__title {
        font-weight: 600;
        font-size: 20px;
        line-height: 30px;
        letter-spacing: 0.4px;
        color: #2c2a29;
      }

      .info-block__text {
        font-size: 16px;
        line-height: 24px;
        letter-spacing: 0.4px;
        color: #2c2a29;
      }
      /* End info-block */

      /* pfc-block */
      .pfc-block {
      }

      .pfc-block__title {
        font-weight: bold;
        font-size: 18px;
        line-height: 30px;
        letter-spacing: 0.4px;
        color: #7db360;
        margin-bottom: 3px;
      }

      .pfc-block__title_orange {
        color: #ed8a15;
      }
      .pfc-block__title_red {
        color: #e04e39;
      }
      .pfc-block__title_dark-red {
        color: #c23e2b;
      }

      .pfc-block__text {
        font-weight: 600;
        font-size: 10px;
        line-height: 20px;
        letter-spacing: 0.4px;
        color: #707070;
        margin-bottom: 20px;
      }

      .pfc-block__progress-bar-container {
        padding-right: 50px;
      }
      /* End pfc-block */

      /* bar */
      .bar {
        background: transparent;
        border-radius: 5px;
        overflow: unset;
      }

      .bar .progress-bar {
        position: relative;
        border-radius: 5px;
        overflow: unset;
      }

      .bar_red .progress-bar {
        background: #e04e39;
      }

      .bar_yellow .progress-bar {
        background: #ffb931;
      }

      .bar_green .progress-bar {
        background: #7db360;
      }

      .bar__label {
        position: absolute;
        left: 100%;
        padding-left: 4px;

        font-weight: 500;
        font-size: 10px;
        line-height: 23px;
        letter-spacing: 0.4px;
        color: #707070;
      }
      /* End bar */

      /* table-block */
      .table-block {
        font-weight: 500;
        font-size: 10px;
        line-height: 23px;
        letter-spacing: 0.4px;
        color: #707070;
        vertical-align: middle;
      }

      .table-block strong {
        font-weight: 600;
        font-size: 14px;
        line-height: 30px;
        color: #2c2a29;
      }

      .table-block tbody tr:nth-of-type(even) {
        background: #f7f7f7;
      }
      /* End table-block */
    </style>
  </head>
  <body>
    <div class="page">
      <div class="header">
        <div class="container-fluid page__container">
          <div class="row">
            <div class="col">
              <span class="header__brand">
                <img src="cid:logo.png" alt="" class="header__logo" />
                <h1 class="header__title">Сервис «Рекомендации»</h1>
              </span>
            </div>
          </div>
        </div>
      </div>
      <div class="page__content">
        <div class="container-fluid page__container">
          <div class="row mb-4">
            <div class="col">
              <div class="info-block">
                <h2 class="info-block__title mb-3">Уважаемый посетитель!</h2>
                <p class="info-block__text">
                  Спасибо, что Вы заботитесь о своем здоровье и воспользовались
                  сервисом рекомендаций в центре "Мои документы".
                </p>
                <p class="info-block__text">
                  От вашего имени успешно зарегистрирована учетная запись.
                </p>
				<p class="info-block__text">
					Для доступа к <a href="http://food-service-guidelines.paladin-mobile.ru/">Личному кабинету</a> в качестве логина для входа можно использовать адрес этой электронной почты.
                </p><p class="info-block__text">
                  Ваш логин: <?=$client['email']?><br>
				  Ваш пароль: <?=$client['pass']?>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
	<?/*?>
	<!--[if mso | IE]></td></tr></table><table align="center" border="0" cellpadding="0" cellspacing="0" class="" role="presentation" style="width:600px;" width="600" bgcolor="#ffffff" ><tr><td style="line-height:0px;font-size:0px;mso-line-height-rule:exactly;"><![endif]-->
    <div style="background:#ffffff;background-color:#ffffff;margin:0px auto;max-width:600px;">
      <table align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="background:#ffffff;background-color:#ffffff;width:100%;">
        <tbody>
          <tr>
            <td style="direction:ltr;font-size:0px;padding:20px 0;text-align:center;">
              <!--[if mso | IE]><table role="presentation" border="0" cellpadding="0" cellspacing="0"><tr><td class="" style="vertical-align:top;width:600px;" ><![endif]-->
              <div class="mj-column-per-100 mj-outlook-group-fix" style="font-size:0px;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%;">
                <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="vertical-align:top;" width="100%">
                  <tbody>
                    <tr>
                      <td align="center" vertical-align="middle" style="font-size:0px;padding:10px 25px;word-break:break-word;">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate;line-height:100%;">
                          <tbody>
                            <tr>
                              <td align="center" bgcolor="#7db360" role="presentation" style="border:none;border-radius:3px;cursor:auto;mso-padding-alt:10px 25px;background:#7db360;" valign="middle">
                                <a href="https://fsg.paladin-mobile.ru/u/nutrition.pdf" style="display:inline-block;background:#7db360;color:#ffffff;font-family:Inter, sans-serif;font-size:13px;font-weight:normal;line-height:120%;margin:0;text-decoration:none;text-transform:none;padding:10px 25px;mso-padding-alt:0px;border-radius:3px;" target="_blank"> Путеводитель по здоровому питанию </a>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!--[if mso | IE]></td></tr></table><![endif]-->
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!--[if mso | IE]></td></tr></table><![endif]-->
	<?*/?>
  </body>
</html>
