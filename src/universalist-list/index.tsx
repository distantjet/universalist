import { registerBlockType, BlockConfiguration } from '@wordpress/blocks';
import Edit from './edit';
import metadata from './block.json';
import DistantJet_UniversalistIcon from "../components/icon";


// Cast metadata to any or BlockConfiguration to satisfy the registerBlockType signature
registerBlockType(metadata.name as string, {
    ...metadata,
    edit: Edit,
    icon: DistantJet_UniversalistIcon,
    save: () => null,
} as BlockConfiguration);
