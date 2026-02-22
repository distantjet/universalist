import { __ } from '@wordpress/i18n';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { store as blockEditorStore } from '@wordpress/block-editor';
import { TemplateArray, BlockInstance } from '@wordpress/blocks';
import { ReactElement, useEffect } from 'react';

// Define a more specific type for the list item blocks to avoid 'any' errors
interface ListItemBlock extends BlockInstance {
    attributes: {
        content: string;
        [key: string]: any;
    };
}

export default function Edit({ attributes, setAttributes, clientId }): ReactElement {
    const blockProps = useBlockProps();

    const TEMPLATE: TemplateArray = [
        ['core/group', { className: 'universalist-primary-wrapper' }, [
            ['core/paragraph', { content: 'Primary language list below:', textColor: 'accent' }],
            ['core/list', { className: 'universalist-list-primary' }]
        ]],
        ['core/group', { className: 'universalist-secondary-wrapper' }, [
            ['core/paragraph', { content: 'Secondary language list Below:', textColor: 'accent' }],
            ['core/list', { className: 'universalist-list-secondary' }]
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

        const items_primary: string[] = [];
        const items_secondary: string[] = [];

        innerBlocks.forEach((group) => {
            group.innerBlocks.forEach((block) => {
                if (block.name === 'core/list') {
                    const className = block.attributes.className || '';
                    
                    const listItems = block.innerBlocks.map(
                        (li: ListItemBlock) => li.attributes.content
                    );

                    if (className.includes('universalist-list-primary')) {
                        items_primary.push(...listItems);
                    }

                    if (className.includes('universalist-list-secondary')) {
                        items_secondary.push(...listItems);
                    }
                }
            });
        });

        // Only update if the data has actually changed
        const hasChanged = 
            JSON.stringify(items_primary) !== JSON.stringify(attributes.items_primary) ||
            JSON.stringify(items_secondary) !== JSON.stringify(attributes.items_secondary);

        if (hasChanged) {
            setAttributes({ items_primary, items_secondary });
        }

    }, [innerBlocks, attributes.items_primary, attributes.items_secondary, setAttributes]);

    return (
        <div {...blockProps}>
            <InnerBlocks template={TEMPLATE} templateLock="all" />
        </div>
    );
}