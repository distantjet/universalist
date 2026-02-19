import { __ } from '@wordpress/i18n';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { store as blockEditorStore } from '@wordpress/block-editor';
import { TemplateArray } from '@wordpress/blocks';
import { ReactElement, useEffect } from 'react';

export default function Edit({ attributes, setAttributes, clientId }): ReactElement {
    const blockProps = useBlockProps({
        className: 'grid grid-cols-2 gap-4 border p-4'
    });

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

    // 🔍 Get this block’s inner blocks using its OWN clientId
    const innerBlocks = useSelect(
        (select) => select(blockEditorStore).getBlocks(clientId),
        [clientId]
    );

    // 🧠 Extract list items and update attributes
    useEffect(() => {
        if (!innerBlocks) return;

        const items_en: string[] = [];
        const items_es: string[] = [];

        innerBlocks.forEach((group) => {
            group.innerBlocks.forEach((block) => {
                if (block.name === 'core/list') {
                    const className = block.attributes.className || '';
                    const listItems = block.innerBlocks.map(
                        (li) => li.attributes.content
                    );

                    if (className.includes('universalist-list-en')) {
                        items_en.push(...listItems);
                    }

                    if (className.includes('universalist-list-es')) {
                        items_es.push(...listItems);
                    }
                }
            });
        });

        setAttributes({ items_en, items_es });

    }, [innerBlocks]);

    return (
        <div {...blockProps}>
            <InnerBlocks template={TEMPLATE} templateLock="all" />
        </div>
    );
}
