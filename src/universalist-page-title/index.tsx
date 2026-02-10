import './style.css';
import './editor.css';
import './document-panel';
import DistantJet_UniversalistIcon from "../components/icon";
import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';


registerBlockType(metadata.name, {
    icon: DistantJet_UniversalistIcon,
    edit: () => null,
    save: () => null,
});