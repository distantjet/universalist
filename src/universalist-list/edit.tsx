import { __ } from '@wordpress/i18n';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { store as blockEditorStore } from '@wordpress/block-editor';
import { TemplateArray, BlockInstance } from '@wordpress/blocks'; // Added BlockInstance
import { ReactElement, useEffect } from 'react';

// Define a more specific type for the list item blocks to avoid 'any' errors
interface ListItemBlock extends BlockInstance {
    attributes: {
        content: string;
        [key: string]: any;
    };
}

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

    // 🔍 Get this block’s inner blocks
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
                    
                    // Typing 'li' as ListItemBlock solves the implicit 'any' error
                    const listItems = block.innerBlocks.map(
                        (li: ListItemBlock) => li.attributes.content
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

        // Optimization: Only update if the data has actually changed
        const hasChanged = 
            JSON.stringify(items_en) !== JSON.stringify(attributes.items_en) ||
            JSON.stringify(items_es) !== JSON.stringify(attributes.items_es);

        if (hasChanged) {
            setAttributes({ items_en, items_es });
        }

    }, [innerBlocks, attributes.items_en, attributes.items_es, setAttributes]);

    return (
        <div {...blockProps}>
            <InnerBlocks template={TEMPLATE} templateLock="all" />
        </div>
    );
}