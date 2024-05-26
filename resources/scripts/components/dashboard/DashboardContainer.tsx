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

  useEffect(() => {
    if (!servers) return;
    if (servers.pagination.currentPage > 1 && !servers.items.length) {
      setPage(1);
    }
  }, [servers?.pagination.currentPage]);

  useEffect(() => {
    window.history.replaceState(null, document.title, `/${page <= 1 ? '' : `?page=${page}`}`);
  }, [page]);

  useEffect(() => {
    if (error) clearAndAddHttpError({ key: 'dashboard', error });
    if (!error) clearFlashes('dashboard');
  }, [error]);

  const [adminServersOrder, setAdminServersOrder] = useState<string[]>([]);
  const [nonAdminServersOrder, setNonAdminServersOrder] = useState<string[]>([]);


  useEffect(() => {
    const adminSavedOrder = localStorage.getItem(`admin:serversOrder:${uuid}`);
    const nonAdminSavedOrder = localStorage.getItem(`nonadmin:serversOrder:${uuid}`);
    setAdminServersOrder(adminSavedOrder ? JSON.parse(adminSavedOrder) : []);
    setNonAdminServersOrder(nonAdminSavedOrder ? JSON.parse(nonAdminSavedOrder) : []);
  }, [uuid]);


  useEffect(() => {
    if (!servers) return;
    const newOrder = servers.items.map((server) => server.uuid);

    if (showOnlyAdmin) {
      if (!localStorage.getItem(`admin:serversOrder:${uuid}`) || newOrder.length !== adminServersOrder.length) {
        setAdminServersOrder(newOrder);
        localStorage.setItem(`admin:serversOrder:${uuid}`, JSON.stringify(newOrder));
      }
    } else {
      if (!localStorage.getItem(`nonadmin:serversOrder:${uuid}`) || newOrder.length !== nonAdminServersOrder.length) {
        setNonAdminServersOrder(newOrder);
        localStorage.setItem(`nonadmin:serversOrder:${uuid}`, JSON.stringify(newOrder));
      }
    }
  }, [servers, uuid, showOnlyAdmin]);


  useEffect(() => {
    localStorage.setItem(`admin:serversOrder:${uuid}`, JSON.stringify(adminServersOrder));
    localStorage.setItem(`nonadmin:serversOrder:${uuid}`, JSON.stringify(nonAdminServersOrder));
  }, [adminServersOrder, nonAdminServersOrder]);


  const onDragEnd = (result: DropResult) => {
    const { source, destination } = result;

    if (!destination) {
      return;
    }

    const newOrder = Array.from(showOnlyAdmin ? adminServersOrder : nonAdminServersOrder);
    newOrder.splice(source.index, 1);
    newOrder.splice(destination.index, 0, (showOnlyAdmin ? adminServersOrder : nonAdminServersOrder)[source.index]);

    if (showOnlyAdmin) {
      setAdminServersOrder(newOrder);
      localStorage.setItem(`admin:serversOrder:${uuid}`, JSON.stringify(newOrder));
    } else {
      setNonAdminServersOrder(newOrder);
      localStorage.setItem(`nonadmin:serversOrder:${uuid}`, JSON.stringify(newOrder));
    }
  };


  const serversOrder = showOnlyAdmin ? adminServersOrder : nonAdminServersOrder;

  return (
    <PageContentBlock title={'Dashboard'} showFlashKey={'dashboard'}>
      {rootAdmin && (
        <div css={tw`mb-2 flex justify-end items-center`}>
          <p css={tw`uppercase text-xs text-neutral-400 mr-2`}>
            {showOnlyAdmin ? t('showing-others-servers') : t('showing-your-servers')}
          </p>
          <Switch name={'show_all_servers'} defaultChecked={showOnlyAdmin} onChange={() => setShowOnlyAdmin(s => !s)} />
        </div>
      )}
      <div css={tw`mb-2 flex justify-end items-center`}>
        <p css={tw`uppercase text-xs text-neutral-400 mr-2`}>
          {allowDragDrop ? t('sorting_disabled') : t('sorting_enabled')}
        </p>
        <Switch
          name={'allow_drag_drop'}
          defaultChecked={!allowDragDrop}
          onChange={() => setAllowDragDrop(!allowDragDrop)}
        />
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
                    <Draggable key={server.uuid} draggableId={server.uuid} index={index} isDragDisabled={allowDragDrop}>
                      {(provided) => (
                        <div
                          ref={provided.innerRef}
                          {...provided.draggableProps}
                          {...provided.dragHandleProps}
                        >
                          <ServerRow server={server} css={index > 0 ? tw`mt-2` : undefined} />
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