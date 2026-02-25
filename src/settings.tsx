import './style.css';
import React, { useEffect, useRef, useState} from "react";
import ReactDOM from "react-dom/client";
import { languages } from "./languages";
import { DistantjetUniversalistSettingsObj } from "./types/com.distantjet.types";
import Axios, {isAxiosError} from "axios";
import qs from "qs";

declare const distantjetUniversalistSettingsObj: DistantjetUniversalistSettingsObj;


document.addEventListener('DOMContentLoaded', function(){


	const DistantJet_Universalist_Settings: React.FC<DistantjetUniversalistSettingsObj> = ({ajaxurl, nonce, selected_language_primary, selected_language_secondary}) => 
	{
        const ddlLanguagePrimary = useRef<HTMLSelectElement | null>(null);
        const ddlLanguageSecondary = useRef<HTMLSelectElement | null>(null);

        const [selectedLanguagePrimary, setSelectedLanguagePrimary] = useState(selected_language_primary);
        const [selectedLanguageSecondary, setSelectedLanguageSecondary] = useState(selected_language_secondary);

        const targetLanguagePrimary = languages.find(lang => lang.locale === selectedLanguagePrimary);
        const targetLanguageSecondary = languages.find(lang => lang.locale === selectedLanguageSecondary);

        const targetLanguageNamePrimary = targetLanguagePrimary?.name;
        const targetLanguageNameSecondary = targetLanguageSecondary?.name;

        const [availableSecondaryLanguages, setAvailableSecondaryLanguages] = useState(languages);

        const messageRef = useRef<HTMLDivElement>(null);

        const languageSwitcherRef = useRef<HTMLTextAreaElement>(null);

        function handlePrimaryLanguageChange(e: React.ChangeEvent<HTMLSelectElement>) {

            setSelectedLanguageSecondary('');
            setAvailableSecondaryLanguages(languages);

            setSelectedLanguagePrimary(e.target.value);

            setAvailableSecondaryLanguages(languages.filter(lang => lang.locale !== e.target.value));
        }

        function handleSecondaryLanguageChange(e: React.ChangeEvent<HTMLSelectElement>) {

            setSelectedLanguageSecondary(e.target.value);
        }

        function showMessage(type, message) {

            if(messageRef.current) {

                messageRef.current.innerText = '';

                const messageHtml = `
                
                    <div class="notice notice-${type} is-dismissible">
                        <p><strong>${message}</strong></p>
                        <button type="button" class="notice-dismiss" onclick="this.parentElement.remove();">
                            <span class="screen-reader-text">Dismiss</span>
                        </button>
                    </div>
                `;
    
                messageRef.current.innerHTML = messageHtml;
            }
        }

        function handleSaveChanges() {

            console.log(selectedLanguagePrimary + ' ' + selectedLanguageSecondary);

            const selectionPrimary = ddlLanguagePrimary.current?.value;
            const selectionSecondary = ddlLanguageSecondary.current?.value;

            const saveSettingsRequest = Axios.CancelToken.source();

            async function saveChanges() {

                if(selectionPrimary && selectionSecondary) {

                    let parameters = {

                        action: 'save_settings',
                        nonce: nonce,
                        lang_selection_primary: selectionPrimary,
                        lang_selection_secondary: selectionSecondary
                    }

                    try {

                        if(selectedLanguagePrimary == selectedLanguageSecondary) {

                            throw Error('Primary language and secondary language must be different');
                        }

                        const response = await Axios.post(ajaxurl, qs.stringify(parameters));

                        showMessage('success', response.data.data.message);

                    }
                    catch(error) {

                        if(isAxiosError(error)) {

                            // TypeScript now knows 'error' has a 'response' property
                            const serverMessage = error.response?.data?.data?.message || 'Server Error';
                            showMessage('error', serverMessage);
                        }
                        else if(error instanceof Error) {

                            // This handles regular JS errors (like syntax or null pointers)
                            showMessage('error', error.message);
                        }
                        else {

                            showMessage('error', 'An unexpected error occurred.');
                        }
                    }
                }
            }

            saveChanges();

            return () => saveSettingsRequest.cancel();
        }

        const handleGenerateSwitcherLinks = () => {

            console.log(selectedLanguageSecondary);

            try {
                if(selectedLanguagePrimary == '') {

                    throw Error('Select a primary language');
                }
                
                if(selectedLanguageSecondary == '') {

                    throw Error('Select a secondary language');
                }
                
                if(languageSwitcherRef.current) {
    
                    languageSwitcherRef.current.classList.toggle('djet-univ-settings__textarea--hidden');
    
                    const switcherHtml = `
                    <div style="display: flex; margin:1rem 0;">
                        <a href="#" onclick="document.cookie='distantjet_univ_lang_cookie=${selectedLanguagePrimary};path=/;max-age=2592000';location.reload();return false;">${targetLanguageNamePrimary}</a>
                        <div>|</div>
                        <a href="#" onclick="document.cookie='distantjet_univ_lang_cookie=${selectedLanguageSecondary};path=/;max-age=2592000';location.reload();return false;">${targetLanguageNameSecondary}</a>
                    </div>
                    `;
    
                    languageSwitcherRef.current.innerHTML = switcherHtml;
                }
            }
            catch(error) {

                console.log(error.message);
            }
        }

		return(

            <>
                <h1 style={{marginBottom: '1rem'}}>Universalist settings</h1>
                
                <strong>Choose the primary and secondary languages for your site. </strong>
                <p className="description">If a visitor’s browser language matches your secondary language, the plugin will display your secondary‑language content. Otherwise, it will default to the primary language.</p>
                <p className="description">To create ready‑to‑use language‑switch links you can add anywhere on your site, click <strong>“Generate Language Switcher Links.”</strong></p>

                <div style={{marginTop: '1rem'}}ref={messageRef}></div>

                <table className="form-table" role="presentation">
                    <tr>
                        <th scope="row">
                            <label htmlFor="dj_universalist_lang_pri">Primary language</label>
                        </th>
                        <td>
                            <select ref={ddlLanguagePrimary} value={selectedLanguagePrimary} name="dj_universalist_lang_pri" id="dj_universalist_lang_pri" onChange={handlePrimaryLanguageChange}>
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
                            <select ref={ddlLanguageSecondary} value={selectedLanguageSecondary} name="dj_universalist_lang_sec" id="dj_universalist_lang_sec" onChange={handleSecondaryLanguageChange}>
                                <option value="">Select secondary language</option>
                                {availableSecondaryLanguages.map(secLang => (
                                    <option value={secLang.locale}>{secLang.name}</option>
                                ))}

                            </select>
                        </td>
                    </tr>
                </table>

                <p className="submit">
                    <button className="button button-primary" onClick={handleSaveChanges}>Save Changes</button>
                </p>

                <hr />

                <h3>Language Switcher Links</h3>

                <p className="submit">
                    <button className="button button-primary" onClick={handleGenerateSwitcherLinks}>Generate Switcher links</button>
                </p>

                <div className="djet-univ-settings">

                    <textarea ref={languageSwitcherRef} className="djet-univ-settings__textarea djet-univ-settings__textarea--hidden"></textarea>
                </div>


            </>			

		)
	}

	const root = ReactDOM.createRoot(document.querySelector("#distantjetUniversalistSettingsApp") as HTMLElement);

	root.render(<DistantJet_Universalist_Settings  {...distantjetUniversalistSettingsObj}/>);



});