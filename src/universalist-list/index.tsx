import { registerBlockType, BlockConfiguration } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';
import Edit from './edit';
import metadata from './block.json';
import DistantJet_UniversalistIcon from "../components/icon";

registerBlockType(metadata.name as string, {
    ...metadata,
    edit: Edit,
    icon: DistantJet_UniversalistIcon,
    save: () => <InnerBlocks.Content />, 
} as BlockConfiguration);