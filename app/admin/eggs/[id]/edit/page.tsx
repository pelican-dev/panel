import { EggVariable } from "@/components/admin/forms/EggVariablesTab";
import EditEggClient from './pageClient';
import { headers } from 'next/headers';

type EggAttributes = {
  id: number | null;
  uuid: string | null;
  name: string;
  author: string | null;
  description: string | null;
  tags?: string[];
  features: string[];
  docker_image?: string | null;
  docker_images?: Record<string, string>;
  startup?: string | null;
  created_at: string | null;
  updated_at: string | null;
  config?: any;
  script?: any;
  variables?: EggVariable[];
};

async function fetchEgg(idOrUuid: string): Promise<EggAttributes> {
  const hdrs = await headers();
  const host = hdrs.get('host') ?? 'localhost:3000';
  const isHttps = process.env.VERCEL_ENV || process.env.NODE_ENV === 'production';
  const base = process.env.NEXT_PUBLIC_BASE_URL ?? `${isHttps ? 'https' : 'http'}://${host}`;
  const tryUuid = await fetch(`${base}/api/application/eggs/${idOrUuid}`, { cache: 'no-store' });
  if (tryUuid.ok) {
    const j = await tryUuid.json();
    return j.attributes as EggAttributes;
  }
  // fallback: list and find by id
  const list = await fetch(`${base}/api/application/eggs?per_page=500`, { cache: 'no-store' });
  if (!list.ok) throw new Error('Failed to load egg');
  const lj = await list.json();
  const data = (lj?.data ?? []) as Array<{ attributes: EggAttributes }>;
  const found = data.find((d) => String(d.attributes.id) === idOrUuid)?.attributes;
  if (!found) throw new Error('Egg not found');
  return found;
}

export default async function EditEggPage({ params }: { params: Promise<{ id: string }> }) {
  const { id } = await params;
  const attr = await fetchEgg(id);
  const dockerImages = Object.entries(attr.docker_images ?? {}).map(([key, value]) => ({ key, value }));

  const initial = {
    id: attr.id ?? undefined,
    uuid: attr.uuid ?? undefined,
    name: attr.name,
    author: attr.author ?? '',
    description: attr.description ?? '',
    tags: attr.tags ?? [],
    startup: attr.startup ?? '',
    dockerImages,
    // placeholders for other tabs
    stopCommand: 'stop',
    startupConfig: JSON.stringify(attr?.config?.startup ?? {}, null, 2),
    configFiles: JSON.stringify(attr?.config?.files ?? {}, null, 2),
    logConfig: JSON.stringify(attr?.config?.logs ?? [], null, 2),
    variables: attr.variables ?? [],
    scriptContainer: attr?.script?.container ?? '',
    scriptEntry: attr?.script?.entry ?? 'bash',
    installScript: attr?.script?.install ?? '',
  } as any;

  return (
    <div className="mx-auto max-w-[1472px] px-3">
      <EditEggClient initial={initial} />
    </div>
  );
}

