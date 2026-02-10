import { __ } from '@wordpress/i18n';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { TemplateArray } from '@wordpress/blocks';
import { ReactElement } from 'react';

export default function Edit(): ReactElement {
    const blockProps = useBlockProps({ 
        className: 'grid grid-cols-2 gap-4 border p-4' 
    });

    // Defining the template with explicit TemplateArray type
    const TEMPLATE: TemplateArray = [
        ['core/group', { className: 'universalist-en-wrapper' }, [
            ['core/paragraph', { content: 'English List below:', textColor: 'accent' }],
            ['core/list', { className: 'universalist-list-en' }]
        ]],
        ['core/group', { className: 'universalist-es-wrapper' }, [
            ['core/paragraph', { content: 'Lista en Español:', textColor: 'accent' }],
            ['core/list', { className: 'universalist-list-es' }]
        ]]
    ];

    return (
        <div {...blockProps}>
            <InnerBlocks 
                template={ TEMPLATE }
                templateLock="all"
            />
        </div>
    );
}