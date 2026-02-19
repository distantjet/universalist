import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import Edit from './edit';
import metadata from './block.json';
import DistantJet_UniversalistIcon from "../components/icon";

/**
 * We use 'any' or 'unknown' casting for the metadata object if 
 * JSON module resolution causes strict type conflicts with registerBlockType.
 */
registerBlockType( metadata as any, {
	/**
	 * @see ./edit.tsx
	 */
	edit: Edit,
	icon: DistantJet_UniversalistIcon
	// 'save' is omitted because 'render' is defined in block.json (Dynamic Block)
} );