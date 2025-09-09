import { NextRequest, NextResponse } from 'next/server';
import { getEm } from '@/lib/orm';
import { Egg } from '@/entities/panel/Egg';

export async function GET(request: NextRequest) {
  try {
    const { searchParams } = new URL(request.url);
    const page = Math.max(1, parseInt(searchParams.get('page') || '1', 10));
    const perPage = Math.max(1, parseInt(searchParams.get('per_page') || '50', 10));
    const q = (searchParams.get('q') || '').toLowerCase();
    const tag = (searchParams.get('tag') || '').toLowerCase();
    const feature = (searchParams.get('feature') || '').toLowerCase();

    // Load eggs from database only
    const em = await getEm();
    
    // Build filters
    const filters: any = {};
    if (q) {
      filters.$or = [
        { name: { $ilike: `%${q}%` } },
        { description: { $ilike: `%${q}%` } }
      ];
    }
    if (tag) {
      filters.tags = { $overlap: [tag] };
    }
    if (feature) {
      filters.features = { $overlap: [feature] };
    }

    // Get total count and paginated results
    const [eggs, total] = await em.findAndCount(Egg, filters, {
      orderBy: { name: 'ASC' },
      limit: perPage,
      offset: (page - 1) * perPage,
      populate: ['variables'],
    });

    const data = eggs.map((egg) => {
      const dockerImages = egg.dockerImages ?? {};
      const docker_image = dockerImages
        ? dockerImages[Object.keys(dockerImages)[0]]
        : null;

      return {
        object: 'egg',
        attributes: {
          id: egg.id,
          uuid: egg.uuid ?? null,
          name: egg.name,
          author: null,
          description: egg.description ?? null,
          tags: egg.tags ?? [],
          features: egg.features ?? [],
          docker_image,
          docker_images: dockerImages,
          config: {
            files: egg.configFiles ?? {},
            startup: egg.configStartup ?? {},
            stop: egg.configStop ?? '',
            logs: egg.configLogs ?? [],
            file_denylist: egg.fileDenylist ?? [],
            extends: null,
          },
          startup: egg.startup ?? null,
          script: {
            container: egg.scriptContainer ?? null,
            entry: egg.scriptEntry ?? null,
            install: egg.installScript ?? null,
          },
          variables: egg.variables.getItems().map(v => ({
            name: v.name,
            description: v.description,
            env_variable: v.envVariable,
            default_value: v.defaultValue,
            user_viewable: v.userViewable,
            user_editable: v.userEditable,
            rules: v.rules ?? [],
            sort: v.sort,
          })),
          created_at: egg.createdAt ? egg.createdAt.toISOString() : null,
          updated_at: egg.updatedAt ? egg.updatedAt.toISOString() : null,
        },
      };
    });

    return NextResponse.json({
      object: 'list',
      data,
      meta: {
        pagination: {
          total,
          count: data.length,
          per_page: perPage,
          current_page: page,
          total_pages: Math.max(1, Math.ceil(total / perPage)),
          links: {},
        },
      },
    });
  } catch (error) {
    console.error('Get eggs error:', error);
    return NextResponse.json({
      object: 'list',
      data: [],
      meta: {
        pagination: { total: 0, count: 0, per_page: 0, current_page: 1, total_pages: 1, links: {} },
      },
    });
  }
}
