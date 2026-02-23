import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { TextareaControl } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { registerPlugin } from '@wordpress/plugins';

import { store as editorStore } from '@wordpress/editor';

interface PostMeta {
    distantjet_universalist_page_title_primary?: string;
    distantjet_universalist_page_title_secondary?: string;
    [key: string]: any;
}

const UniversalistPageTitlePanel: React.FC = () => {
    
    
    const postType = useSelect(
        (select) => select(editorStore).getCurrentPostType() as string,
        []
    );

    const [meta, setMeta] = useEntityProp('postType', postType, 'meta') as [
        PostMeta | undefined,
        (value: Partial<PostMeta>) => void,
        any
    ];

    if (!meta) return null;

    const update = (key: keyof PostMeta, value: string): void => {
        setMeta({ [key]: value });
    };

    return (
        <PluginDocumentSettingPanel
            name="universalist-page-title"
            title="Universalist Page Title"
        >
            <TextareaControl
                label="English Page Title"
                value={meta.distantjet_universalist_page_title_primary || ''}
                onChange={(v: string) => update('distantjet_universalist_page_title_primary', v)}
            />

            <TextareaControl
                label="Spanish Page Title"
                value={meta.distantjet_universalist_page_title_secondary || ''}
                onChange={(v: string) => update('distantjet_universalist_page_title_secondary', v)}
            />
        </PluginDocumentSettingPanel>
    );
};

registerPlugin('universalist-page-title-panel', {
    render: UniversalistPageTitlePanel,
});