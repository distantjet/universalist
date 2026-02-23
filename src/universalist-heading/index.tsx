import { registerBlockType, BlockConfiguration } from '@wordpress/blocks';
import Edit from './edit';
import metadata from './block.json';
import { BlockAttributes } from './types';
import DistantJet_UniversalistIcon from "../components/icon";

// Cast the metadata to BlockConfiguration to satisfy the overload requirements
registerBlockType<BlockAttributes>(metadata.name, {
    ...(metadata as BlockConfiguration<BlockAttributes>),
    edit: Edit,
    save: () => null,
    icon: DistantJet_UniversalistIcon
});