import './style.css';
import React, { useEffect, useState} from "react";
import ReactDOM from "react-dom/client";
import { languages } from "./languages";
import { DistantjetUniversalistSettingsObj } from "./types/com.distantjet.types";

declare const distantjetUniversalistSettingsObj: DistantjetUniversalistSettingsObj;


document.addEventListener('DOMContentLoaded', function(){


	const DistantJet_Universalist_Settings: React.FC<DistantjetUniversalistSettingsObj> = ({ajaxurl, nonce, selected_language_primary, selected_language_secondary}) => 
	{
        const [selectedPrimaryLanguage, setSelectedPrimaryLanguage] = useState('en');
        const [selectedSecondaryLanguage, setSelectedSecondaryLanguage] = useState('es');

        const [availableSecondaryLanguages, setAvailableSecondaryLanguages] = useState(languages);

        console.log(selected_language_primary);

        function handlePrimaryLanguageChange(e: React.ChangeEvent<HTMLSelectElement>) {

            setSelectedSecondaryLanguage('');
            setAvailableSecondaryLanguages(languages);

            setSelectedPrimaryLanguage(e.target.value);

            setAvailableSecondaryLanguages(languages.filter(lang => lang.locale !== e.target.value));
        }

        function handleSecondaryLanguageChange(e: React.ChangeEvent<HTMLSelectElement>) {

            setSelectedSecondaryLanguage(e.target.value);
        }

		return(

            <>
                <h1>Universalist settings</h1>
                <p className="description">Select the primary and secondary languages you want to use</p>

                <table className="form-table" role="presentation">
                    <tr>
                        <th scope="row">
                            <label htmlFor="dj_universalist_lang_pri">Primary language</label>
                        </th>
                        <td>
                            <select value={selected_language_primary} name="dj_universalist_lang_pri" id="dj_universalist_lang_pri" onChange={handlePrimaryLanguageChange}>
                                <option value="">Select primary language</option>
                                {languages.map(lang => (
                                    <option value={lang.locale} >{lang.name}</option>
                                ))}

                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label htmlFor="dj_universalist_lang_sec">Secondary language</label>
                        </th>
                        <td>
                            <select value={selected_language_secondary} name="dj_universalist_lang_sec" id="dj_universalist_lang_sec" onChange={handleSecondaryLanguageChange}>
                                <option value="">Select secondary language</option>
                                {availableSecondaryLanguages.map(secLang => (
                                    <option value={secLang.locale}>{secLang.name}</option>
                                ))}

                            </select>
                        </td>
                    </tr>
                </table>

                <p className="submit">
                    <input type="submit" name="submit" id="submit" className="button button-primary" value="Save Changes" />
                </p>
            </>			

		)
	}

	const root = ReactDOM.createRoot(document.querySelector("#distantjetUniversalistSettingsApp") as HTMLElement);

	root.render(<DistantJet_Universalist_Settings  {...distantjetUniversalistSettingsObj}/>);



});