<?php

if (!defined('ABSPATH')) die;

class DistantJet_Universalist_Settings
{

    private static $instance = null;

    private $languages = [
        ['locale' => 'en', 'name' => 'English'],
        ['locale' => 'es', 'name' => 'Spanish'],
        ['locale' => 'fr', 'name' => 'French'],
        ['locale' => 'pt', 'name' => 'Portuguese'],
        ['locale' => 'de', 'name' => 'German'],
        ['locale' => 'it', 'name' => 'Italian'],
        ['locale' => 'nl', 'name' => 'Dutch'],
        ['locale' => 'zh', 'name' => 'Chinese'],
        ['locale' => 'ja', 'name' => 'Japanese'],
        ['locale' => 'ko', 'name' => 'Korean'],
        ['locale' => 'ru', 'name' => 'Russian'],
        ['locale' => 'ar', 'name' => 'Arabic'],
        ['locale' => 'hi', 'name' => 'Hindi'],
        ['locale' => 'bn', 'name' => 'Bengali'],
        ['locale' => 'ur', 'name' => 'Urdu'],
        ['locale' => 'fa', 'name' => 'Persian'],
        ['locale' => 'el', 'name' => 'Greek'],
        ['locale' => 'he', 'name' => 'Hebrew'],
        ['locale' => 'tr', 'name' => 'Turkish'],
        ['locale' => 'id', 'name' => 'Indonesian'],
        ['locale' => 'ms', 'name' => 'Malay'],
        ['locale' => 'sv', 'name' => 'Swedish'],
        ['locale' => 'nb', 'name' => 'Norwegian (Bokmål)'],
        ['locale' => 'nn', 'name' => 'Norwegian (Nynorsk)'],
        ['locale' => 'da', 'name' => 'Danish'],
        ['locale' => 'pl', 'name' => 'Polish'],
        ['locale' => 'cs', 'name' => 'Czech'],
        ['locale' => 'sk', 'name' => 'Slovak'],
        ['locale' => 'hu', 'name' => 'Hungarian'],
        ['locale' => 'ro', 'name' => 'Romanian'],
        ['locale' => 'uk', 'name' => 'Ukrainian'],
        ['locale' => 'bg', 'name' => 'Bulgarian'],
        ['locale' => 'sr', 'name' => 'Serbian'],
        ['locale' => 'hr', 'name' => 'Croatian'],
        ['locale' => 'bs', 'name' => 'Bosnian'],
        ['locale' => 'sl', 'name' => 'Slovenian'],
        ['locale' => 'lt', 'name' => 'Lithuanian'],
        ['locale' => 'lv', 'name' => 'Latvian'],
        ['locale' => 'et', 'name' => 'Estonian'],
    ];




    public static function get_instance()
    {

        if (null === self::$instance) {

            self::$instance = new self();
        }

        return self::$instance;
    }

    function get_page()
    {

?>

        <h1>Universalist settings</h1>
        <p class="description">Select the primary and secondary languages you want to use</p>

        <form action="" method="post">
            
            <input type="hidden" name="distantjet_universalist_settings_form_submitted" value="yes">

            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">
                        <label for="dj_universalist_lang_pri">Primary language</label>
                    </th>
                    <td>
                        <select name="dj_universalist_lang_pri" id="dj_universalist_lang_pri">
                            <?php foreach($this->languages as $language) : ?>

                                <option value="<?php echo $language['locale']; ?>"><?php echo $language['name']; ?> </option>

                            
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="dj_universalist_lang_sec">Secondary language</label>
                    </th>
                    <td>
                        <select name="dj_universalist_lang_pri" id="dj_universalist_lang_pri">
                            <option value="">Select</option>
                            <option value="en-US">United States – English</option>
                            <option value="en-GB">United Kingdom – English</option>
                            <option value="en-CA">Canada – English</option>
                            <option value="en-AU">Australia – English</option>
                            <option value="en-NZ">New Zealand – English</option>
                            <option value="en-IE">Ireland – English</option>
                            <option value="en-ZA">South Africa – English</option>
                            <option value="en-IN">India – English</option>
                            <option value="en-SG">Singapore – English</option>
                            <option value="en-PH">Philippines – English</option>
                            <option value="en-HK">Hong Kong – English</option>

                            <option value="es-ES">Spain – Spanish</option>
                            <option value="es-AR">Argentina – Spanish</option>
                            <option value="es-MX">Mexico – Spanish</option>
                            <option value="es-CO">Colombia – Spanish</option>
                            <option value="es-CL">Chile – Spanish</option>
                            <option value="es-PE">Peru – Spanish</option>
                            <option value="es-UY">Uruguay – Spanish</option>
                            <option value="es-PY">Paraguay – Spanish</option>
                            <option value="es-BO">Bolivia – Spanish</option>
                            <option value="es-EC">Ecuador – Spanish</option>
                            <option value="es-VE">Venezuela – Spanish</option>
                            <option value="es-GT">Guatemala – Spanish</option>
                            <option value="es-CR">Costa Rica – Spanish</option>
                            <option value="es-PA">Panama – Spanish</option>
                            <option value="es-DO">Dominican Republic – Spanish</option>
                            <option value="es-HN">Honduras – Spanish</option>
                            <option value="es-NI">Nicaragua – Spanish</option>
                            <option value="es-SV">El Salvador – Spanish</option>
                            <option value="es-PR">Puerto Rico – Spanish</option>

                            <option value="fr-FR">France – French</option>
                            <option value="fr-CA">Canada – French</option>
                            <option value="fr-BE">Belgium – French</option>
                            <option value="fr-CH">Switzerland – French</option>
                            <option value="fr-LU">Luxembourg – French</option>
                            <option value="fr-MC">Monaco – French</option>

                            <option value="pt-PT">Portugal – Portuguese</option>
                            <option value="pt-BR">Brazil – Portuguese</option>

                            <option value="de-DE">Germany – German</option>
                            <option value="de-AT">Austria – German</option>
                            <option value="de-CH">Switzerland – German</option>
                            <option value="de-LU">Luxembourg – German</option>
                            <option value="de-BE">Belgium – German</option>

                            <option value="it-IT">Italy – Italian</option>
                            <option value="it-CH">Switzerland – Italian</option>

                            <option value="nl-NL">Netherlands – Dutch</option>
                            <option value="nl-BE">Belgium – Dutch</option>

                            <option value="zh-CN">China – Chinese (Simplified)</option>
                            <option value="zh-SG">Singapore – Chinese (Simplified)</option>
                            <option value="zh-HK">Hong Kong – Chinese (Traditional)</option>
                            <option value="zh-TW">Taiwan – Chinese (Traditional)</option>
                            <option value="zh-MO">Macau – Chinese (Traditional)</option>

                            <option value="ja-JP">Japan – Japanese</option>
                            <option value="ko-KR">South Korea – Korean</option>

                            <option value="ru-RU">Russia – Russian</option>
                            <option value="ru-UA">Ukraine – Russian</option>
                            <option value="ru-KZ">Kazakhstan – Russian</option>
                            <option value="ru-BY">Belarus – Russian</option>

                            <option value="ar-SA">Saudi Arabia – Arabic</option>
                            <option value="ar-EG">Egypt – Arabic</option>
                            <option value="ar-AE">United Arab Emirates – Arabic</option>
                            <option value="ar-DZ">Algeria – Arabic</option>
                            <option value="ar-MA">Morocco – Arabic</option>
                            <option value="ar-TN">Tunisia – Arabic</option>
                            <option value="ar-LB">Lebanon – Arabic</option>
                            <option value="ar-JO">Jordan – Arabic</option>
                            <option value="ar-IQ">Iraq – Arabic</option>
                            <option value="ar-KW">Kuwait – Arabic</option>
                            <option value="ar-QA">Qatar – Arabic</option>
                            <option value="ar-BH">Bahrain – Arabic</option>
                            <option value="ar-OM">Oman – Arabic</option>
                            <option value="ar-SY">Syria – Arabic</option>
                            <option value="ar-YE">Yemen – Arabic</option>
                            <option value="ar-LY">Libya – Arabic</option>
                            <option value="ar-SD">Sudan – Arabic</option>

                            <option value="hi-IN">India – Hindi</option>

                            <option value="bn-BD">Bangladesh – Bengali</option>
                            <option value="bn-IN">India – Bengali</option>

                            <option value="ur-PK">Pakistan – Urdu</option>
                            <option value="ur-IN">India – Urdu</option>

                            <option value="fa-IR">Iran – Persian</option>
                            <option value="fa-AF">Afghanistan – Dari Persian</option>

                            <option value="el-GR">Greece – Greek</option>
                            <option value="el-CY">Cyprus – Greek</option>

                            <option value="he-IL">Israel – Hebrew</option>

                            <option value="tr-TR">Turkey – Turkish</option>
                            <option value="tr-CY">Cyprus – Turkish</option>

                            <option value="id-ID">Indonesia – Indonesian</option>

                            <option value="ms-MY">Malaysia – Malay</option>
                            <option value="ms-SG">Singapore – Malay</option>
                            <option value="ms-BN">Brunei – Malay</option>

                            <option value="sv-SE">Sweden – Swedish</option>
                            <option value="sv-FI">Finland – Swedish</option>

                            <option value="nb-NO">Norway – Norwegian (Bokmål)</option>
                            <option value="nn-NO">Norway – Norwegian (Nynorsk)</option>

                            <option value="da-DK">Denmark – Danish</option>

                            <option value="pl-PL">Poland – Polish</option>

                            <option value="cs-CZ">Czech Republic – Czech</option>

                            <option value="sk-SK">Slovakia – Slovak</option>

                            <option value="hu-HU">Hungary – Hungarian</option>

                            <option value="ro-RO">Romania – Romanian</option>
                            <option value="ro-MD">Moldova – Romanian</option>

                            <option value="uk-UA">Ukraine – Ukrainian</option>

                            <option value="bg-BG">Bulgaria – Bulgarian</option>

                            <option value="sr-RS">Serbia – Serbian</option>
                            <option value="sr-BA">Bosnia & Herzegovina – Serbian</option>
                            <option value="sr-ME">Montenegro – Serbian</option>

                            <option value="hr-HR">Croatia – Croatian</option>
                            <option value="hr-BA">Bosnia & Herzegovina – Croatian</option>

                            <option value="bs-BA">Bosnia & Herzegovina – Bosnian</option>

                            <option value="sl-SI">Slovenia – Slovenian</option>

                            <option value="lt-LT">Lithuania – Lithuanian</option>

                            <option value="lv-LV">Latvia – Latvian</option>

                            <option value="et-EE">Estonia – Estonian</option>
                        </select>
                    </td>
                </tr>
            </table>

            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
            </p>
        </form>

<?php
    }
}

function universalist_settings()
{

    return DistantJet_Universalist_Settings::get_instance();
}

universalist_settings();
