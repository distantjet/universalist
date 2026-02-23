import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl } from '@wordpress/components';
import { BlockEditProps } from '@wordpress/blocks';
import { BlockAttributes } from './types';

export default function Edit({ attributes, setAttributes }: BlockEditProps<BlockAttributes>) {
    const { heading_primary, heading_secondary, heading_style } = attributes;

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
                <PanelBody title={__('Language Settings', 'universalist-heading')}>
                    <TextControl
                        label={__('Primary language heading', 'universalist-heading')}
                        value={heading_primary}
                        onChange={(val: string) => setAttributes({ heading_primary: val })}
                    />
                    <TextControl
                        label={__('Secondary language heading', 'universalist-heading')}
                        value={heading_secondary}
                        onChange={(val: string) => setAttributes({ heading_secondary: val })}
                    />
                    <SelectControl
                        label={__('Heading Style', 'universalist-heading')}
                        value={heading_style}
                        options={styleOptions}
                        onChange={(val: string) =>
                            setAttributes({ heading_style: val as BlockAttributes['heading_style'] })
                        }
                    />
                </PanelBody>
            </InspectorControls>

            <div {...useBlockProps()}>
                <RichText
                    tagName={heading_style}
                    value={heading_primary}
                    onChange={(val: string) => setAttributes({ heading_primary: val })}
                    placeholder={__('Primary language heading', 'universalist-heading')}
                />
                <small style={{ opacity: 0.5, display: 'block' }}>
                    {__('Secondary language heading:', 'universalist-heading')} {heading_secondary || '...'}
                </small>
            </div>
        </>
    );
}