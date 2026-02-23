import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextareaControl } from '@wordpress/components';
import { BlockEditProps } from '@wordpress/blocks';

export interface Attributes {
	text_primary: string;
	text_secondary: string;
}

const Edit = ( { attributes, setAttributes }: BlockEditProps<Attributes> ) => {
	const { text_primary, text_secondary } = attributes;

	const onChangeEn = ( value: string ) => setAttributes( { text_primary: value } );
	const onChangeEs = ( value: string ) => setAttributes( { text_secondary: value } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Bilingual Text', 'universalist-text' ) }>
					<TextareaControl
						label={ __( 'Primary language Text', 'universalist-text' ) }
						value={ text_primary }
						onChange={ onChangeEn }
					/>
					<TextareaControl
						label={ __( 'Secondary language Text', 'universalist-text' ) }
						value={ text_secondary }
						onChange={ onChangeEs }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...useBlockProps() }>
				<RichText
					tagName="div"
					value={ text_primary }
					onChange={ onChangeEn }
					placeholder={ __( 'Write English text…', 'universalist-text' ) }
				/>
			</div>
		</>
	);
};

export default Edit;