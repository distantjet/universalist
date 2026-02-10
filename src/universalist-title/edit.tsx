import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl } from '@wordpress/components';
import { BlockEditProps } from '@wordpress/blocks';
import { BlockAttributes } from './types';

export default function Edit({ attributes, setAttributes }: BlockEditProps<BlockAttributes>) {
    const { title_en, title_es, title_style } = attributes;

    // Type-safe options for the style selector
    const styleOptions = [
        { label: 'H1', value: 'h1' },
        { label: 'H2', value: 'h2' },
        { label: 'H3', value: 'h3' },
        { label: 'H4', value: 'h4' },
        { label: 'H5', value: 'h5' },
        { label: 'H6', value: 'h6' },
    ];

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Language Settings', 'universalist-title')}>
                    <TextControl
                        label={__('English Title', 'universalist-title')}
                        value={title_en}
                        onChange={(val: string) => setAttributes({ title_en: val })}
                    />
                    <TextControl
                        label={__('Spanish Title', 'universalist-title')}
                        value={title_es}
                        onChange={(val: string) => setAttributes({ title_es: val })}
                    />
                    <SelectControl
                        label={__('Title Style', 'universalist-title')}
                        value={title_style}
                        options={styleOptions}
                        onChange={(val: string) =>
                            setAttributes({ title_style: val as BlockAttributes['title_style'] })
                        }
                    />
                </PanelBody>
            </InspectorControls>

            <div {...useBlockProps()}>
                <RichText
                    tagName={title_style}
                    value={title_en}
                    onChange={(val: string) => setAttributes({ title_en: val })}
                    placeholder={__('Write English text…', 'universalist-title')}
                />
                <small style={{ opacity: 0.5, display: 'block' }}>
                    {__('Spanish Translation:', 'universalist-title')} {title_es || '...'}
                </small>
            </div>
        </>
    );
}