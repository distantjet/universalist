import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import Edit from './edit';
import metadata from './block.json';
import DistantJet_UniversalistIcon from "../components/icon";

registerBlockType( metadata as any, {
	/**
	 * @see ./edit.tsx
	 */
	edit: Edit,
	icon: DistantJet_UniversalistIcon
} );