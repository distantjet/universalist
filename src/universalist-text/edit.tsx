import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextareaControl } from '@wordpress/components';
import { BlockEditProps } from '@wordpress/blocks';

export interface Attributes {
	text_en: string;
	text_es: string;
}

const Edit = ( { attributes, setAttributes }: BlockEditProps<Attributes> ) => {
	const { text_en, text_es } = attributes;

	const onChangeEn = ( value: string ) => setAttributes( { text_en: value } );
	const onChangeEs = ( value: string ) => setAttributes( { text_es: value } );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Bilingual Text', 'universalist-text' ) }>
					<TextareaControl
						label={ __( 'English Text', 'universalist-text' ) }
						value={ text_en }
						onChange={ onChangeEn }
					/>
					<TextareaControl
						label={ __( 'Spanish Text', 'universalist-text' ) }
						value={ text_es }
						onChange={ onChangeEs }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...useBlockProps() }>
				<RichText
					tagName="div"
					value={ text_en }
					onChange={ onChangeEn }
					placeholder={ __( 'Write English text…', 'universalist-text' ) }
				/>
			</div>
		</>
	);
};

export default Edit;