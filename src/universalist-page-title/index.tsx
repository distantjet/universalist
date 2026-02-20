import './document-panel';
import DistantJet_UniversalistIcon from "../components/icon";
import { registerBlockType, BlockConfiguration } from '@wordpress/blocks';
import metadataJson from './block.json';

const metadata = (metadataJson as unknown) as BlockConfiguration;

registerBlockType(metadata, {
    icon: DistantJet_UniversalistIcon,
    edit: () => null,
    save: () => null,
});