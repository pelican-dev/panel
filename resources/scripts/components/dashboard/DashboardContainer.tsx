import React, { useEffect, useState } from 'react';
import { Server } from '@/api/server/getServer';
import getServers from '@/api/getServers';
import ServerRow from '@/components/dashboard/ServerRow';
import Spinner from '@/components/elements/Spinner';
import PageContentBlock from '@/components/elements/PageContentBlock';
import useFlash from '@/plugins/useFlash';
import { useStoreState } from 'easy-peasy';
import { usePersistedState } from '@/plugins/usePersistedState';
import Switch from '@/components/elements/Switch';
import tw from 'twin.macro';
import useSWR from 'swr';
import { PaginatedResult } from '@/api/http';
import { useLocation } from 'react-router-dom';
import { useTranslation } from 'react-i18next';
import { DragDropContext, Droppable, Draggable, DropResult } from 'react-beautiful-dnd';

export default () => {
    const [allowDragDrop, setAllowDragDrop] = useState(true);
    const { t } = useTranslation('dashboard/index');
    const { search } = useLocation();
    const defaultPage = Number(new URLSearchParams(search).get('page') || '1');

    const [page, setPage] = useState(!isNaN(defaultPage) && defaultPage > 0 ? defaultPage : 1);
    const { clearFlashes, clearAndAddHttpError } = useFlash();
    const uuid = useStoreState((state) => state.user.data!.uuid);
    const rootAdmin = useStoreState((state) => state.user.data!.rootAdmin);
    const [showOnlyAdmin, setShowOnlyAdmin] = usePersistedState(`${uuid}:show_all_servers`, false);

    const { data: servers, error } = useSWR<PaginatedResult<Server>>(
        ['/api/client/servers', showOnlyAdmin && rootAdmin, page],
        () => getServers({ page, type: showOnlyAdmin && rootAdmin ? 'admin' : undefined })
    );

    const [groups, setGroups] = useState<{ [key: string]: string[] }>({ default: [] });
    const [currentGroup, setCurrentGroup] = useState('default');
    const [newGroupName, setNewGroupName] = useState('');
    const [showMoveOptions, setShowMoveOptions] = useState<{ [key: string]: boolean }>({});

    useEffect(() => {
        if (servers) {
            const allServers = servers.items.map((server) => server.uuid);
            setGroups((prevGroups) => {
                if (!prevGroups['default'].length) {
                    return { ...prevGroups, default: allServers };
                }
                return prevGroups;
            });
        }
    }, [servers]);

    useEffect(() => {
        if (servers?.pagination?.currentPage && servers.pagination.currentPage > 1 && servers.items?.length === 0) {
            setPage(1);
        }
    }, [servers?.pagination?.currentPage]);

    useEffect(() => {
        window.history.replaceState(null, document.title, `/${page <= 1 ? '' : `?page=${page}`}`);
    }, [page]);

    useEffect(() => {
        if (error) clearAndAddHttpError({ key: 'dashboard', error });
        if (!error) clearFlashes('dashboard');
    }, [error]);

    useEffect(() => {
        const savedGroups = localStorage.getItem(`groups:${uuid}`);
        if (savedGroups) {
            setGroups(JSON.parse(savedGroups));
        }
    }, [uuid]);

    useEffect(() => {
        localStorage.setItem(`groups:${uuid}`, JSON.stringify(groups));
    }, [groups]);

    const addGroup = () => {
        if (newGroupName && !groups[newGroupName]) {
            setGroups({ ...groups, [newGroupName]: [] });
            setNewGroupName('');
        }
    };

    const deleteGroup = (group: string) => {
        const updatedGroups = { ...groups };
        delete updatedGroups[group];
        setGroups(updatedGroups);
        if (currentGroup === group) {
            setCurrentGroup('default');
        }
    };

    const moveServerToGroup = (serverUuid: string, targetGroup: string) => {
        if (!groups[targetGroup]) {
            console.warn(`Group ${targetGroup} does not exist`);
            return;
        }

        const updatedGroups = { ...groups };
        const currentGroup = Object.keys(updatedGroups).find((group) => updatedGroups[group].includes(serverUuid));
        if (currentGroup) {
            updatedGroups[currentGroup] = updatedGroups[currentGroup].filter((uuid) => uuid !== serverUuid);
        }
        updatedGroups[targetGroup].push(serverUuid);
        setGroups(updatedGroups);
        setShowMoveOptions((prevState) => ({ ...prevState, [serverUuid]: false }));
    };

    const onDragEnd = (result: DropResult) => {
        const { source, destination } = result;

        if (!destination) {
            return;
        }

        const newOrder = Array.from(groups[currentGroup]);
        newOrder.splice(source.index, 1);
        newOrder.splice(destination.index, 0, groups[currentGroup][source.index]);

        setGroups({ ...groups, [currentGroup]: newOrder });
    };

    const serversOrder = groups[currentGroup];

    return (
        <PageContentBlock title={t('title')} showFlashKey={'dashboard'}>
            {rootAdmin && (
                <div css={tw`mb-4 flex justify-between items-center`}>
                    <div css={tw`flex items-center`}>
                        <p css={tw`uppercase text-xs text-neutral-400 mr-2`}>
                            {allowDragDrop ? t('sorting_disabled') : t('sorting_enabled')}
                        </p>
                        <Switch
                            name={'allow_drag_drop'}
                            defaultChecked={!allowDragDrop}
                            onChange={() => setAllowDragDrop(!allowDragDrop)}
                        />
                    </div>
                    <div css={tw`flex items-center`}>
                        <p css={tw`uppercase text-xs text-neutral-400 mr-2`}>
                            {showOnlyAdmin ? t('showing-others-servers') : t('showing-your-servers')}
                        </p>
                        <Switch
                            name={'show_all_servers'}
                            defaultChecked={showOnlyAdmin}
                            onChange={() => setShowOnlyAdmin((s) => !s)}
                        />
                    </div>
                </div>
            )}
            <div css={tw`mb-4 flex justify-between items-center`}>
                <div css={tw`flex items-center`}>
                    <select
                        css={tw`p-2 border border-neutral-600 rounded bg-neutral-700 text-white`}
                        value={currentGroup}
                        onChange={(e) => setCurrentGroup(e.target.value)}
                    >
                        {Object.keys(groups).map((group) => (
                            <option key={group} value={group}>
                                {group === 'default' ? t('all_servers') : group}
                            </option>
                        ))}
                    </select>
                    <input
                        css={tw`ml-2 p-2 border border-neutral-600 rounded bg-neutral-700 text-white`}
                        type='text'
                        value={newGroupName}
                        onChange={(e) => setNewGroupName(e.target.value)}
                        placeholder={t('new_group_name')}
                    />
                    <button css={tw`ml-2 p-2 bg-blue-600 text-white rounded`} onClick={addGroup}>
                        {t('add_group')}
                    </button>
                </div>
                {currentGroup !== 'default' && (
                    <button css={tw`p-2 bg-red-600 text-white rounded`} onClick={() => deleteGroup(currentGroup)}>
                        {t('delete_group')}
                    </button>
                )}
            </div>
            {!servers ? (
                <Spinner centered size={'large'} />
            ) : (
                <DragDropContext onDragEnd={onDragEnd}>
                    <Droppable droppableId='servers'>
                        {(provided) => (
                            <div {...provided.droppableProps} ref={provided.innerRef}>
                                {serversOrder.map((serverUuid, index) => {
                                    const server = servers.items.find((s) => s.uuid === serverUuid);
                                    if (!server) {
                                        console.warn(`Server with uuid ${serverUuid} not found`);
                                        return null;
                                    }
                                    return (
                                        <Draggable
                                            key={server.uuid}
                                            draggableId={server.uuid}
                                            index={index}
                                            isDragDisabled={allowDragDrop}
                                        >
                                            {(provided) => (
                                                <div
                                                    ref={provided.innerRef}
                                                    {...provided.draggableProps}
                                                    {...provided.dragHandleProps}
                                                    css={tw`mb-2 p-4 text-white`}
                                                >
                                                    <ServerRow server={server} />
                                                    <div css={tw`mt-2 flex justify-end`}>
                                                        <button
                                                            css={tw`p-2 bg-blue-600 text-white rounded`}
                                                            onClick={() =>
                                                                setShowMoveOptions((prevState) => ({
                                                                    ...prevState,
                                                                    [server.uuid]: !prevState[server.uuid],
                                                                }))
                                                            }
                                                        >
                                                            {t('move_server')}
                                                        </button>
                                                        {showMoveOptions[server.uuid] && (
                                                            <select
                                                                css={tw`ml-2 p-2 border border-neutral-600 rounded bg-neutral-700 text-white`}
                                                                value={currentGroup}
                                                                onChange={(e) =>
                                                                    moveServerToGroup(server.uuid, e.target.value)
                                                                }
                                                            >
                                                                {Object.keys(groups).map((group) => (
                                                                    <option key={group} value={group}>
                                                                        {group === 'default' ? t('all_servers') : group}
                                                                    </option>
                                                                ))}
                                                            </select>
                                                        )}
                                                    </div>
                                                </div>
                                            )}
                                        </Draggable>
                                    );
                                })}
                                {provided.placeholder}
                            </div>
                        )}
                    </Droppable>
                </DragDropContext>
            )}
        </PageContentBlock>
    );
};
