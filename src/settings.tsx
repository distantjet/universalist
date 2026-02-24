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

        const [selectedPrimaryLanguage, setSelectedPrimaryLanguage] = useState(selected_language_primary);
        const [selectedSecondaryLanguage, setSelectedSecondaryLanguage] = useState(selected_language_secondary);

        const [availableSecondaryLanguages, setAvailableSecondaryLanguages] = useState(languages);

        const messageRef = useRef<HTMLDivElement>(null);

        function handlePrimaryLanguageChange(e: React.ChangeEvent<HTMLSelectElement>) {

            setSelectedSecondaryLanguage('');
            setAvailableSecondaryLanguages(languages);

            setSelectedPrimaryLanguage(e.target.value);

            setAvailableSecondaryLanguages(languages.filter(lang => lang.locale !== e.target.value));
        }

        function handleSecondaryLanguageChange(e: React.ChangeEvent<HTMLSelectElement>) {

            setSelectedSecondaryLanguage(e.target.value);
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

		return(

            <>
                <h1>Universalist settings</h1>
                <div ref={messageRef}></div>
                <p className="description">Select the primary and secondary languages you want to use</p>

                <table className="form-table" role="presentation">
                    <tr>
                        <th scope="row">
                            <label htmlFor="dj_universalist_lang_pri">Primary language</label>
                        </th>
                        <td>
                            <select ref={ddlLanguagePrimary} value={selectedPrimaryLanguage} name="dj_universalist_lang_pri" id="dj_universalist_lang_pri" onChange={handlePrimaryLanguageChange}>
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
                            <select ref={ddlLanguageSecondary} value={selectedSecondaryLanguage} name="dj_universalist_lang_sec" id="dj_universalist_lang_sec" onChange={handleSecondaryLanguageChange}>
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

            </>			

		)
	}

	const root = ReactDOM.createRoot(document.querySelector("#distantjetUniversalistSettingsApp") as HTMLElement);

	root.render(<DistantJet_Universalist_Settings  {...distantjetUniversalistSettingsObj}/>);



});