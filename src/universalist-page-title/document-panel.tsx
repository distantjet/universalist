import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { TextareaControl } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { registerPlugin } from '@wordpress/plugins';

import { store as editorStore } from '@wordpress/editor';

interface PostMeta {
    dj_universalist_page_title_en?: string;
    dj_universalist_page_title_es?: string;
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
                value={meta.dj_universalist_page_title_en || ''}
                onChange={(v: string) => update('dj_universalist_page_title_en', v)}
            />

            <TextareaControl
                label="Spanish Page Title"
                value={meta.dj_universalist_page_title_es || ''}
                onChange={(v: string) => update('dj_universalist_page_title_es', v)}
            />
        </PluginDocumentSettingPanel>
    );
};

registerPlugin('universalist-page-title-panel', {
    render: UniversalistPageTitlePanel,
});