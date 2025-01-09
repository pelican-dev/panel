--
-- PostgreSQL database dump
--

-- Dumped from database version 17.2
-- Dumped by pg_dump version 17.2 (Debian 17.2-1.pgdg120+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: activity_log_subjects; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.activity_log_subjects (
    id bigint NOT NULL,
    activity_log_id bigint NOT NULL,
    subject_type character varying(255) NOT NULL,
    subject_id bigint NOT NULL
);


--
-- Name: activity_log_subjects_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.activity_log_subjects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: activity_log_subjects_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.activity_log_subjects_id_seq OWNED BY public.activity_log_subjects.id;


--
-- Name: activity_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.activity_logs (
    id bigint NOT NULL,
    batch character(36),
    event character varying(255) NOT NULL,
    ip character varying(255) NOT NULL,
    description text,
    actor_type character varying(255),
    actor_id bigint,
    properties jsonb NOT NULL,
    "timestamp" timestamp(0) without time zone NOT NULL,
    api_key_id integer
);


--
-- Name: activity_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.activity_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: activity_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.activity_logs_id_seq OWNED BY public.activity_logs.id;


--
-- Name: allocations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.allocations (
    id integer NOT NULL,
    node_id integer NOT NULL,
    ip character varying(255) NOT NULL,
    port integer NOT NULL,
    server_id integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    ip_alias text,
    notes character varying(255)
);


--
-- Name: allocations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.allocations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: allocations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.allocations_id_seq OWNED BY public.allocations.id;


--
-- Name: api_keys; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.api_keys (
    id integer NOT NULL,
    token text NOT NULL,
    allowed_ips text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    user_id integer NOT NULL,
    memo text,
    identifier character(16),
    key_type smallint DEFAULT '0'::smallint NOT NULL,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    permissions jsonb NOT NULL
);


--
-- Name: api_keys_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.api_keys_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: api_keys_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.api_keys_id_seq OWNED BY public.api_keys.id;


--
-- Name: api_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.api_logs (
    id integer NOT NULL,
    authorized boolean NOT NULL,
    error text,
    key character(16),
    method character(6) NOT NULL,
    route text NOT NULL,
    content text,
    user_agent text NOT NULL,
    request_ip inet NOT NULL,
    created_at timestamp(0) with time zone,
    updated_at timestamp(0) with time zone
);


--
-- Name: api_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.api_logs_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: api_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.api_logs_id_seq OWNED BY public.api_logs.id;


--
-- Name: audit_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.audit_logs (
    id bigint NOT NULL,
    uuid character(36) NOT NULL,
    is_system boolean DEFAULT false NOT NULL,
    user_id integer,
    server_id integer,
    action character varying(255) NOT NULL,
    subaction character varying(255),
    device jsonb NOT NULL,
    metadata jsonb NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


--
-- Name: audit_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.audit_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: audit_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.audit_logs_id_seq OWNED BY public.audit_logs.id;


--
-- Name: backups; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.backups (
    id bigint NOT NULL,
    server_id integer NOT NULL,
    uuid character(36) NOT NULL,
    name character varying(255) NOT NULL,
    ignored_files text NOT NULL,
    disk character varying(255) NOT NULL,
    checksum character varying(255),
    bytes bigint DEFAULT '0'::bigint NOT NULL,
    completed_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    is_successful boolean DEFAULT false NOT NULL,
    upload_id text,
    is_locked smallint DEFAULT '0'::smallint NOT NULL
);


--
-- Name: backups_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.backups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: backups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.backups_id_seq OWNED BY public.backups.id;


--
-- Name: database_host_node; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.database_host_node (
    id bigint NOT NULL,
    node_id integer NOT NULL,
    database_host_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: database_host_node_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.database_host_node_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: database_host_node_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.database_host_node_id_seq OWNED BY public.database_host_node.id;


--
-- Name: database_hosts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.database_hosts (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    host character varying(255) NOT NULL,
    port integer NOT NULL,
    username character varying(255) NOT NULL,
    password text NOT NULL,
    max_databases integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    driver character varying(255) NOT NULL
);


--
-- Name: database_servers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.database_servers_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: database_servers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.database_servers_id_seq OWNED BY public.database_hosts.id;


--
-- Name: databases; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.databases (
    id integer NOT NULL,
    server_id integer NOT NULL,
    database_host_id integer NOT NULL,
    database character varying(255) NOT NULL,
    username character varying(255) NOT NULL,
    remote character varying(255) DEFAULT '%'::character varying NOT NULL,
    password text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    max_connections integer DEFAULT 0
);


--
-- Name: databases_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.databases_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: databases_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.databases_id_seq OWNED BY public.databases.id;


--
-- Name: egg_mount; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.egg_mount (
    egg_id integer NOT NULL,
    mount_id integer NOT NULL
);


--
-- Name: egg_variables; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.egg_variables (
    id integer NOT NULL,
    egg_id integer NOT NULL,
    name character varying(255) NOT NULL,
    description text NOT NULL,
    env_variable character varying(255) NOT NULL,
    default_value text NOT NULL,
    user_viewable smallint NOT NULL,
    user_editable smallint NOT NULL,
    rules jsonb NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    sort smallint
);


--
-- Name: eggs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.eggs (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    startup text,
    config_from integer,
    config_stop character varying(255),
    config_logs text,
    config_startup text,
    config_files text,
    script_install text,
    script_is_privileged boolean DEFAULT true NOT NULL,
    script_entry character varying(255) DEFAULT 'ash'::character varying NOT NULL,
    script_container character varying(255) DEFAULT 'alpine:3.4'::character varying NOT NULL,
    copy_script_from integer,
    uuid character(36) NOT NULL,
    author character varying(255) NOT NULL,
    features jsonb,
    docker_images jsonb,
    update_url text,
    file_denylist jsonb,
    force_outgoing_ip boolean DEFAULT false NOT NULL,
    tags text NOT NULL
);


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id integer NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    failed_at timestamp(0) without time zone NOT NULL,
    exception text NOT NULL,
    uuid character varying(255)
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: model_has_permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.model_has_permissions (
    permission_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


--
-- Name: model_has_roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.model_has_roles (
    role_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


--
-- Name: mount_node; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.mount_node (
    node_id integer NOT NULL,
    mount_id integer NOT NULL
);


--
-- Name: mount_server; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.mount_server (
    server_id integer NOT NULL,
    mount_id integer NOT NULL
);


--
-- Name: mounts; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.mounts (
    id integer NOT NULL,
    uuid character(36) NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    source character varying(255) NOT NULL,
    target character varying(255) NOT NULL,
    read_only smallint NOT NULL,
    user_mountable smallint NOT NULL
);


--
-- Name: mounts_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.mounts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: mounts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.mounts_id_seq OWNED BY public.mounts.id;


--
-- Name: nodes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.nodes (
    id integer NOT NULL,
    public smallint NOT NULL,
    name character varying(255) NOT NULL,
    fqdn character varying(255) NOT NULL,
    scheme character varying(255) DEFAULT 'https'::character varying NOT NULL,
    memory integer NOT NULL,
    memory_overallocate integer DEFAULT 0 NOT NULL,
    disk integer NOT NULL,
    disk_overallocate integer DEFAULT 0 NOT NULL,
    daemon_token text NOT NULL,
    daemon_listen smallint DEFAULT '8080'::smallint NOT NULL,
    daemon_sftp smallint DEFAULT '2022'::smallint NOT NULL,
    daemon_base character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    upload_size integer DEFAULT 100 NOT NULL,
    behind_proxy boolean DEFAULT false NOT NULL,
    description text,
    maintenance_mode boolean DEFAULT false NOT NULL,
    uuid character(36) NOT NULL,
    daemon_token_id character(16) NOT NULL,
    tags text NOT NULL,
    cpu integer DEFAULT 0 NOT NULL,
    cpu_overallocate integer DEFAULT 0 NOT NULL,
    daemon_sftp_alias character varying(255)
);


--
-- Name: nodes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.nodes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: nodes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.nodes_id_seq OWNED BY public.nodes.id;


--
-- Name: notifications; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.notifications (
    id character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    notifiable_type character varying(255) NOT NULL,
    notifiable_id bigint NOT NULL,
    data jsonb NOT NULL,
    read_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: password_resets; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


--
-- Name: permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.permissions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;


--
-- Name: recovery_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.recovery_tokens (
    id bigint NOT NULL,
    user_id integer NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: recovery_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.recovery_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: recovery_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.recovery_tokens_id_seq OWNED BY public.recovery_tokens.id;


--
-- Name: role_has_permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.role_has_permissions (
    permission_id bigint NOT NULL,
    role_id bigint NOT NULL
);


--
-- Name: roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: schedules; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.schedules (
    id integer NOT NULL,
    server_id integer NOT NULL,
    name character varying(255) NOT NULL,
    cron_day_of_week character varying(255) NOT NULL,
    cron_day_of_month character varying(255) NOT NULL,
    cron_hour character varying(255) NOT NULL,
    cron_minute character varying(255) NOT NULL,
    is_active boolean NOT NULL,
    is_processing boolean NOT NULL,
    last_run_at timestamp(0) without time zone,
    next_run_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    cron_month character varying(255) NOT NULL,
    only_when_online smallint DEFAULT '0'::smallint NOT NULL
);


--
-- Name: schedules_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.schedules_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: schedules_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.schedules_id_seq OWNED BY public.schedules.id;


--
-- Name: server_transfers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.server_transfers (
    id integer NOT NULL,
    server_id integer NOT NULL,
    successful boolean,
    old_node integer NOT NULL,
    new_node integer NOT NULL,
    old_allocation integer NOT NULL,
    new_allocation integer NOT NULL,
    old_additional_allocations jsonb,
    new_additional_allocations jsonb,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    archived boolean DEFAULT false NOT NULL
);


--
-- Name: server_transfers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.server_transfers_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: server_transfers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.server_transfers_id_seq OWNED BY public.server_transfers.id;


--
-- Name: server_variables; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.server_variables (
    id integer NOT NULL,
    server_id integer NOT NULL,
    variable_id integer NOT NULL,
    variable_value text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: server_variables_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.server_variables_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: server_variables_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.server_variables_id_seq OWNED BY public.server_variables.id;


--
-- Name: servers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.servers (
    id integer NOT NULL,
    uuid character(36) NOT NULL,
    uuid_short character(8) NOT NULL,
    node_id integer NOT NULL,
    name character varying(255) NOT NULL,
    owner_id integer NOT NULL,
    memory integer NOT NULL,
    swap integer NOT NULL,
    disk integer NOT NULL,
    io integer NOT NULL,
    cpu integer NOT NULL,
    egg_id integer NOT NULL,
    startup text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    allocation_id integer NOT NULL,
    image character varying(255) NOT NULL,
    description text NOT NULL,
    skip_scripts boolean DEFAULT false NOT NULL,
    external_id character varying(255),
    database_limit integer DEFAULT 0,
    allocation_limit integer,
    threads character varying(255),
    backup_limit integer DEFAULT 0 NOT NULL,
    status character varying(255),
    installed_at timestamp(0) without time zone,
    oom_killer smallint DEFAULT '0'::smallint NOT NULL,
    docker_labels text
);


--
-- Name: servers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.servers_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: servers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.servers_id_seq OWNED BY public.servers.id;


--
-- Name: service_options_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.service_options_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: service_options_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.service_options_id_seq OWNED BY public.eggs.id;


--
-- Name: service_variables_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.service_variables_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: service_variables_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.service_variables_id_seq OWNED BY public.egg_variables.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id integer,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


--
-- Name: settings; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.settings (
    id integer NOT NULL,
    key character varying(255) NOT NULL,
    value text NOT NULL
);


--
-- Name: settings_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.settings_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: settings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.settings_id_seq OWNED BY public.settings.id;


--
-- Name: subusers; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.subusers (
    id integer NOT NULL,
    user_id integer NOT NULL,
    server_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    permissions jsonb
);


--
-- Name: subusers_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.subusers_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: subusers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.subusers_id_seq OWNED BY public.subusers.id;


--
-- Name: tasks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tasks (
    id integer NOT NULL,
    schedule_id integer NOT NULL,
    sequence_id integer NOT NULL,
    action character varying(255) NOT NULL,
    payload text NOT NULL,
    time_offset integer NOT NULL,
    is_queued boolean NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    continue_on_failure smallint DEFAULT '0'::smallint NOT NULL
);


--
-- Name: tasks_id_seq1; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tasks_id_seq1
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tasks_id_seq1; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tasks_id_seq1 OWNED BY public.tasks.id;


--
-- Name: tasks_log; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tasks_log (
    id integer NOT NULL,
    task_id integer NOT NULL,
    run_time timestamp(0) without time zone NOT NULL,
    run_status integer NOT NULL,
    response text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: tasks_log_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tasks_log_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tasks_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tasks_log_id_seq OWNED BY public.tasks_log.id;


--
-- Name: user_ssh_keys; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.user_ssh_keys (
    id integer NOT NULL,
    user_id integer NOT NULL,
    name character varying(255) NOT NULL,
    fingerprint character varying(255) NOT NULL,
    public_key text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: user_ssh_keys_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.user_ssh_keys_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: user_ssh_keys_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.user_ssh_keys_id_seq OWNED BY public.user_ssh_keys.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id integer NOT NULL,
    uuid character(36) NOT NULL,
    email character varying(255) NOT NULL,
    password text NOT NULL,
    remember_token character varying(255),
    language character(5) DEFAULT 'en'::bpchar NOT NULL,
    use_totp smallint NOT NULL,
    totp_secret text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    username character varying(255) NOT NULL,
    gravatar boolean DEFAULT true NOT NULL,
    external_id character varying(255),
    totp_authenticated_at timestamp(0) with time zone,
    timezone character varying(255) DEFAULT 'UTC'::character varying NOT NULL,
    oauth jsonb
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: webhook_configurations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.webhook_configurations (
    id bigint NOT NULL,
    endpoint character varying(255) NOT NULL,
    description character varying(255) NOT NULL,
    events jsonb NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: webhook_configurations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.webhook_configurations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: webhook_configurations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.webhook_configurations_id_seq OWNED BY public.webhook_configurations.id;


--
-- Name: webhooks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.webhooks (
    id bigint NOT NULL,
    webhook_configuration_id bigint NOT NULL,
    event character varying(255) NOT NULL,
    endpoint character varying(255) NOT NULL,
    successful_at timestamp(0) without time zone,
    payload jsonb NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: webhooks_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.webhooks_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: webhooks_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.webhooks_id_seq OWNED BY public.webhooks.id;


--
-- Name: activity_log_subjects id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.activity_log_subjects ALTER COLUMN id SET DEFAULT nextval('public.activity_log_subjects_id_seq'::regclass);


--
-- Name: activity_logs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.activity_logs ALTER COLUMN id SET DEFAULT nextval('public.activity_logs_id_seq'::regclass);


--
-- Name: allocations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.allocations ALTER COLUMN id SET DEFAULT nextval('public.allocations_id_seq'::regclass);


--
-- Name: api_keys id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.api_keys ALTER COLUMN id SET DEFAULT nextval('public.api_keys_id_seq'::regclass);


--
-- Name: api_logs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.api_logs ALTER COLUMN id SET DEFAULT nextval('public.api_logs_id_seq'::regclass);


--
-- Name: audit_logs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.audit_logs ALTER COLUMN id SET DEFAULT nextval('public.audit_logs_id_seq'::regclass);


--
-- Name: backups id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.backups ALTER COLUMN id SET DEFAULT nextval('public.backups_id_seq'::regclass);


--
-- Name: database_host_node id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.database_host_node ALTER COLUMN id SET DEFAULT nextval('public.database_host_node_id_seq'::regclass);


--
-- Name: database_hosts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.database_hosts ALTER COLUMN id SET DEFAULT nextval('public.database_servers_id_seq'::regclass);


--
-- Name: databases id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.databases ALTER COLUMN id SET DEFAULT nextval('public.databases_id_seq'::regclass);


--
-- Name: egg_variables id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.egg_variables ALTER COLUMN id SET DEFAULT nextval('public.service_variables_id_seq'::regclass);


--
-- Name: eggs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.eggs ALTER COLUMN id SET DEFAULT nextval('public.service_options_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: mounts id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.mounts ALTER COLUMN id SET DEFAULT nextval('public.mounts_id_seq'::regclass);


--
-- Name: nodes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.nodes ALTER COLUMN id SET DEFAULT nextval('public.nodes_id_seq'::regclass);


--
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- Name: recovery_tokens id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recovery_tokens ALTER COLUMN id SET DEFAULT nextval('public.recovery_tokens_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: schedules id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedules ALTER COLUMN id SET DEFAULT nextval('public.schedules_id_seq'::regclass);


--
-- Name: server_transfers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.server_transfers ALTER COLUMN id SET DEFAULT nextval('public.server_transfers_id_seq'::regclass);


--
-- Name: server_variables id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.server_variables ALTER COLUMN id SET DEFAULT nextval('public.server_variables_id_seq'::regclass);


--
-- Name: servers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.servers ALTER COLUMN id SET DEFAULT nextval('public.servers_id_seq'::regclass);


--
-- Name: settings id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.settings ALTER COLUMN id SET DEFAULT nextval('public.settings_id_seq'::regclass);


--
-- Name: subusers id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.subusers ALTER COLUMN id SET DEFAULT nextval('public.subusers_id_seq'::regclass);


--
-- Name: tasks id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks ALTER COLUMN id SET DEFAULT nextval('public.tasks_id_seq1'::regclass);


--
-- Name: tasks_log id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks_log ALTER COLUMN id SET DEFAULT nextval('public.tasks_log_id_seq'::regclass);


--
-- Name: user_ssh_keys id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_ssh_keys ALTER COLUMN id SET DEFAULT nextval('public.user_ssh_keys_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: webhook_configurations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.webhook_configurations ALTER COLUMN id SET DEFAULT nextval('public.webhook_configurations_id_seq'::regclass);


--
-- Name: webhooks id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.webhooks ALTER COLUMN id SET DEFAULT nextval('public.webhooks_id_seq'::regclass);


--
-- Name: activity_log_subjects activity_log_subjects_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.activity_log_subjects
    ADD CONSTRAINT activity_log_subjects_pkey PRIMARY KEY (id);


--
-- Name: activity_logs activity_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.activity_logs
    ADD CONSTRAINT activity_logs_pkey PRIMARY KEY (id);


--
-- Name: allocations allocations_node_id_ip_port_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.allocations
    ADD CONSTRAINT allocations_node_id_ip_port_unique UNIQUE (node_id, ip, port);


--
-- Name: allocations allocations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.allocations
    ADD CONSTRAINT allocations_pkey PRIMARY KEY (id);


--
-- Name: api_keys api_keys_identifier_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.api_keys
    ADD CONSTRAINT api_keys_identifier_unique UNIQUE (identifier);


--
-- Name: api_keys api_keys_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.api_keys
    ADD CONSTRAINT api_keys_pkey PRIMARY KEY (id);


--
-- Name: api_logs api_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.api_logs
    ADD CONSTRAINT api_logs_pkey PRIMARY KEY (id);


--
-- Name: audit_logs audit_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.audit_logs
    ADD CONSTRAINT audit_logs_pkey PRIMARY KEY (id);


--
-- Name: backups backups_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.backups
    ADD CONSTRAINT backups_pkey PRIMARY KEY (id);


--
-- Name: backups backups_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.backups
    ADD CONSTRAINT backups_uuid_unique UNIQUE (uuid);


--
-- Name: database_host_node database_host_node_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.database_host_node
    ADD CONSTRAINT database_host_node_pkey PRIMARY KEY (id);


--
-- Name: database_hosts database_servers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.database_hosts
    ADD CONSTRAINT database_servers_pkey PRIMARY KEY (id);


--
-- Name: databases databases_database_host_id_server_id_database_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.databases
    ADD CONSTRAINT databases_database_host_id_server_id_database_unique UNIQUE (database_host_id, server_id, database);


--
-- Name: databases databases_database_host_id_username_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.databases
    ADD CONSTRAINT databases_database_host_id_username_unique UNIQUE (database_host_id, username);


--
-- Name: databases databases_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.databases
    ADD CONSTRAINT databases_pkey PRIMARY KEY (id);


--
-- Name: egg_mount egg_mount_egg_id_mount_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.egg_mount
    ADD CONSTRAINT egg_mount_egg_id_mount_id_unique UNIQUE (egg_id, mount_id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: model_has_permissions model_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);


--
-- Name: model_has_roles model_has_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);


--
-- Name: mount_node mount_node_node_id_mount_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.mount_node
    ADD CONSTRAINT mount_node_node_id_mount_id_unique UNIQUE (node_id, mount_id);


--
-- Name: mount_server mount_server_server_id_mount_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.mount_server
    ADD CONSTRAINT mount_server_server_id_mount_id_unique UNIQUE (server_id, mount_id);


--
-- Name: mounts mounts_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.mounts
    ADD CONSTRAINT mounts_id_unique UNIQUE (id);


--
-- Name: mounts mounts_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.mounts
    ADD CONSTRAINT mounts_name_unique UNIQUE (name);


--
-- Name: mounts mounts_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.mounts
    ADD CONSTRAINT mounts_pkey PRIMARY KEY (id);


--
-- Name: mounts mounts_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.mounts
    ADD CONSTRAINT mounts_uuid_unique UNIQUE (uuid);


--
-- Name: nodes nodes_daemon_token_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.nodes
    ADD CONSTRAINT nodes_daemon_token_id_unique UNIQUE (daemon_token_id);


--
-- Name: nodes nodes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.nodes
    ADD CONSTRAINT nodes_pkey PRIMARY KEY (id);


--
-- Name: nodes nodes_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.nodes
    ADD CONSTRAINT nodes_uuid_unique UNIQUE (uuid);


--
-- Name: notifications notifications_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.notifications
    ADD CONSTRAINT notifications_pkey PRIMARY KEY (id);


--
-- Name: permissions permissions_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- Name: recovery_tokens recovery_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recovery_tokens
    ADD CONSTRAINT recovery_tokens_pkey PRIMARY KEY (id);


--
-- Name: role_has_permissions role_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);


--
-- Name: roles roles_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: schedules schedules_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedules
    ADD CONSTRAINT schedules_pkey PRIMARY KEY (id);


--
-- Name: server_transfers server_transfers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.server_transfers
    ADD CONSTRAINT server_transfers_pkey PRIMARY KEY (id);


--
-- Name: server_variables server_variables_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.server_variables
    ADD CONSTRAINT server_variables_pkey PRIMARY KEY (id);


--
-- Name: servers servers_allocation_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.servers
    ADD CONSTRAINT servers_allocation_id_unique UNIQUE (allocation_id);


--
-- Name: servers servers_external_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.servers
    ADD CONSTRAINT servers_external_id_unique UNIQUE (external_id);


--
-- Name: servers servers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.servers
    ADD CONSTRAINT servers_pkey PRIMARY KEY (id);


--
-- Name: servers servers_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.servers
    ADD CONSTRAINT servers_uuid_unique UNIQUE (uuid);


--
-- Name: servers servers_uuidshort_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.servers
    ADD CONSTRAINT servers_uuidshort_unique UNIQUE (uuid_short);


--
-- Name: eggs service_options_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.eggs
    ADD CONSTRAINT service_options_pkey PRIMARY KEY (id);


--
-- Name: eggs service_options_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.eggs
    ADD CONSTRAINT service_options_uuid_unique UNIQUE (uuid);


--
-- Name: egg_variables service_variables_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.egg_variables
    ADD CONSTRAINT service_variables_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_id_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_id_unique UNIQUE (id);


--
-- Name: settings settings_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.settings
    ADD CONSTRAINT settings_pkey PRIMARY KEY (id);


--
-- Name: subusers subusers_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.subusers
    ADD CONSTRAINT subusers_pkey PRIMARY KEY (id);


--
-- Name: tasks_log tasks_log_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks_log
    ADD CONSTRAINT tasks_log_pkey PRIMARY KEY (id);


--
-- Name: tasks tasks_pkey1; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks
    ADD CONSTRAINT tasks_pkey1 PRIMARY KEY (id);


--
-- Name: user_ssh_keys user_ssh_keys_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_ssh_keys
    ADD CONSTRAINT user_ssh_keys_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users users_username_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_unique UNIQUE (username);


--
-- Name: users users_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_uuid_unique UNIQUE (uuid);


--
-- Name: webhook_configurations webhook_configurations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.webhook_configurations
    ADD CONSTRAINT webhook_configurations_pkey PRIMARY KEY (id);


--
-- Name: webhooks webhooks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.webhooks
    ADD CONSTRAINT webhooks_pkey PRIMARY KEY (id);


--
-- Name: activity_log_subjects_subject_type_subject_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX activity_log_subjects_subject_type_subject_id_index ON public.activity_log_subjects USING btree (subject_type, subject_id);


--
-- Name: activity_logs_actor_type_actor_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX activity_logs_actor_type_actor_id_index ON public.activity_logs USING btree (actor_type, actor_id);


--
-- Name: activity_logs_event_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX activity_logs_event_index ON public.activity_logs USING btree (event);


--
-- Name: audit_logs_action_server_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX audit_logs_action_server_id_index ON public.audit_logs USING btree (action, server_id);


--
-- Name: jobs_queue_reserved_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX jobs_queue_reserved_at_index ON public.jobs USING btree (queue, reserved_at);


--
-- Name: model_has_permissions_model_id_model_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);


--
-- Name: model_has_roles_model_id_model_type_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);


--
-- Name: notifications_notifiable_type_notifiable_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX notifications_notifiable_type_notifiable_id_index ON public.notifications USING btree (notifiable_type, notifiable_id);


--
-- Name: password_resets_email_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);


--
-- Name: password_resets_token_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX password_resets_token_index ON public.password_resets USING btree (token);


--
-- Name: tasks_schedule_id_sequence_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX tasks_schedule_id_sequence_id_index ON public.tasks USING btree (schedule_id, sequence_id);


--
-- Name: users_external_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX users_external_id_index ON public.users USING btree (external_id);


--
-- Name: activity_log_subjects activity_log_subjects_activity_log_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.activity_log_subjects
    ADD CONSTRAINT activity_log_subjects_activity_log_id_foreign FOREIGN KEY (activity_log_id) REFERENCES public.activity_logs(id) ON DELETE CASCADE;


--
-- Name: allocations allocations_node_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.allocations
    ADD CONSTRAINT allocations_node_id_foreign FOREIGN KEY (node_id) REFERENCES public.nodes(id) ON DELETE CASCADE;


--
-- Name: allocations allocations_server_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.allocations
    ADD CONSTRAINT allocations_server_id_foreign FOREIGN KEY (server_id) REFERENCES public.servers(id) ON DELETE SET NULL;


--
-- Name: api_keys api_keys_user_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.api_keys
    ADD CONSTRAINT api_keys_user_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: api_keys api_keys_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.api_keys
    ADD CONSTRAINT api_keys_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: audit_logs audit_logs_server_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.audit_logs
    ADD CONSTRAINT audit_logs_server_id_foreign FOREIGN KEY (server_id) REFERENCES public.servers(id) ON DELETE CASCADE;


--
-- Name: audit_logs audit_logs_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.audit_logs
    ADD CONSTRAINT audit_logs_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: backups backups_server_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.backups
    ADD CONSTRAINT backups_server_id_foreign FOREIGN KEY (server_id) REFERENCES public.servers(id) ON DELETE CASCADE;


--
-- Name: database_host_node database_host_node_database_host_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.database_host_node
    ADD CONSTRAINT database_host_node_database_host_id_foreign FOREIGN KEY (database_host_id) REFERENCES public.database_hosts(id);


--
-- Name: database_host_node database_host_node_node_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.database_host_node
    ADD CONSTRAINT database_host_node_node_id_foreign FOREIGN KEY (node_id) REFERENCES public.nodes(id) ON DELETE CASCADE;


--
-- Name: databases databases_database_host_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.databases
    ADD CONSTRAINT databases_database_host_id_foreign FOREIGN KEY (database_host_id) REFERENCES public.database_hosts(id);


--
-- Name: databases databases_server_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.databases
    ADD CONSTRAINT databases_server_id_foreign FOREIGN KEY (server_id) REFERENCES public.servers(id);


--
-- Name: egg_mount egg_mount_egg_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.egg_mount
    ADD CONSTRAINT egg_mount_egg_id_foreign FOREIGN KEY (egg_id) REFERENCES public.eggs(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: egg_mount egg_mount_mount_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.egg_mount
    ADD CONSTRAINT egg_mount_mount_id_foreign FOREIGN KEY (mount_id) REFERENCES public.mounts(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: eggs eggs_config_from_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.eggs
    ADD CONSTRAINT eggs_config_from_foreign FOREIGN KEY (config_from) REFERENCES public.eggs(id) ON DELETE SET NULL;


--
-- Name: eggs eggs_copy_script_from_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.eggs
    ADD CONSTRAINT eggs_copy_script_from_foreign FOREIGN KEY (copy_script_from) REFERENCES public.eggs(id) ON DELETE SET NULL;


--
-- Name: model_has_permissions model_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: model_has_roles model_has_roles_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: mount_node mount_node_mount_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.mount_node
    ADD CONSTRAINT mount_node_mount_id_foreign FOREIGN KEY (mount_id) REFERENCES public.mounts(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: mount_node mount_node_node_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.mount_node
    ADD CONSTRAINT mount_node_node_id_foreign FOREIGN KEY (node_id) REFERENCES public.nodes(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: mount_server mount_server_mount_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.mount_server
    ADD CONSTRAINT mount_server_mount_id_foreign FOREIGN KEY (mount_id) REFERENCES public.mounts(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: mount_server mount_server_server_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.mount_server
    ADD CONSTRAINT mount_server_server_id_foreign FOREIGN KEY (server_id) REFERENCES public.servers(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: recovery_tokens recovery_tokens_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.recovery_tokens
    ADD CONSTRAINT recovery_tokens_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: schedules schedules_server_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.schedules
    ADD CONSTRAINT schedules_server_id_foreign FOREIGN KEY (server_id) REFERENCES public.servers(id) ON DELETE CASCADE;


--
-- Name: server_transfers server_transfers_server_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.server_transfers
    ADD CONSTRAINT server_transfers_server_id_foreign FOREIGN KEY (server_id) REFERENCES public.servers(id) ON DELETE CASCADE;


--
-- Name: server_variables server_variables_server_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.server_variables
    ADD CONSTRAINT server_variables_server_id_foreign FOREIGN KEY (server_id) REFERENCES public.servers(id) ON DELETE CASCADE;


--
-- Name: server_variables server_variables_variable_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.server_variables
    ADD CONSTRAINT server_variables_variable_id_foreign FOREIGN KEY (variable_id) REFERENCES public.egg_variables(id) ON DELETE CASCADE;


--
-- Name: servers servers_allocation_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.servers
    ADD CONSTRAINT servers_allocation_id_foreign FOREIGN KEY (allocation_id) REFERENCES public.allocations(id);


--
-- Name: servers servers_egg_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.servers
    ADD CONSTRAINT servers_egg_id_foreign FOREIGN KEY (egg_id) REFERENCES public.eggs(id);


--
-- Name: servers servers_node_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.servers
    ADD CONSTRAINT servers_node_id_foreign FOREIGN KEY (node_id) REFERENCES public.nodes(id);


--
-- Name: servers servers_owner_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.servers
    ADD CONSTRAINT servers_owner_id_foreign FOREIGN KEY (owner_id) REFERENCES public.users(id);


--
-- Name: egg_variables service_variables_egg_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.egg_variables
    ADD CONSTRAINT service_variables_egg_id_foreign FOREIGN KEY (egg_id) REFERENCES public.eggs(id) ON DELETE CASCADE;


--
-- Name: subusers subusers_server_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.subusers
    ADD CONSTRAINT subusers_server_id_foreign FOREIGN KEY (server_id) REFERENCES public.servers(id) ON DELETE CASCADE;


--
-- Name: subusers subusers_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.subusers
    ADD CONSTRAINT subusers_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: tasks tasks_schedule_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tasks
    ADD CONSTRAINT tasks_schedule_id_foreign FOREIGN KEY (schedule_id) REFERENCES public.schedules(id) ON DELETE CASCADE;


--
-- Name: user_ssh_keys user_ssh_keys_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.user_ssh_keys
    ADD CONSTRAINT user_ssh_keys_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: webhooks webhooks_webhook_configuration_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.webhooks
    ADD CONSTRAINT webhooks_webhook_configuration_id_foreign FOREIGN KEY (webhook_configuration_id) REFERENCES public.webhook_configurations(id);


--
-- PostgreSQL database dump complete
--

--
-- PostgreSQL database dump
--

-- Dumped from database version 17.2
-- Dumped by pg_dump version 17.2 (Debian 17.2-1.pgdg120+1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	2016_01_23_195641_add_allocations_table	1
2	2016_01_23_195851_add_api_keys	1
3	2016_01_23_200044_add_api_permissions	1
4	2016_01_23_200159_add_downloads	1
5	2016_01_23_200421_create_failed_jobs_table	1
6	2016_01_23_200440_create_jobs_table	1
7	2016_01_23_200528_add_locations	1
8	2016_01_23_200648_add_nodes	1
9	2016_01_23_201433_add_password_resets	1
10	2016_01_23_201531_add_permissions	1
11	2016_01_23_201649_add_server_variables	1
12	2016_01_23_201748_add_servers	1
13	2016_01_23_202544_add_service_options	1
14	2016_01_23_202731_add_service_varibles	1
15	2016_01_23_202943_add_services	1
16	2016_01_23_203119_create_settings_table	1
17	2016_01_23_203150_add_subusers	1
18	2016_01_23_203159_add_users	1
19	2016_01_23_203947_create_sessions_table	1
20	2016_01_25_234418_rename_permissions_column	1
21	2016_02_07_172148_add_databases_tables	1
22	2016_02_07_181319_add_database_servers_table	1
23	2016_02_13_154306_add_service_option_default_startup	1
24	2016_02_20_155318_add_unique_service_field	1
25	2016_02_27_163411_add_tasks_table	1
26	2016_02_27_163447_add_tasks_log_table	1
27	2016_03_18_155649_add_nullable_field_lastrun	1
28	2016_08_30_212718_add_ip_alias	1
29	2016_08_30_213301_modify_ip_storage_method	1
30	2016_09_01_193520_add_suspension_for_servers	1
31	2016_09_01_211924_remove_active_column	1
32	2016_09_02_190647_add_sftp_password_storage	1
33	2016_09_04_171338_update_jobs_tables	1
34	2016_09_04_172028_update_failed_jobs_table	1
35	2016_09_04_182835_create_notifications_table	1
36	2016_09_07_163017_add_unique_identifier	1
37	2016_09_14_145945_allow_longer_regex_field	1
38	2016_09_17_194246_add_docker_image_column	1
39	2016_09_21_165554_update_servers_column_name	1
40	2016_09_29_213518_rename_double_insurgency	1
41	2016_10_07_152117_build_api_log_table	1
42	2016_10_14_164802_update_api_keys	1
43	2016_10_23_181719_update_misnamed_bungee	1
44	2016_10_23_193810_add_foreign_keys_servers	1
45	2016_10_23_201624_add_foreign_allocations	1
46	2016_10_23_202222_add_foreign_api_keys	1
47	2016_10_23_202703_add_foreign_api_permissions	1
48	2016_10_23_202953_add_foreign_database_servers	1
49	2016_10_23_203105_add_foreign_databases	1
50	2016_10_23_203335_add_foreign_nodes	1
51	2016_10_23_203522_add_foreign_permissions	1
52	2016_10_23_203857_add_foreign_server_variables	1
53	2016_10_23_204157_add_foreign_service_options	1
54	2016_10_23_204321_add_foreign_service_variables	1
55	2016_10_23_204454_add_foreign_subusers	1
56	2016_10_23_204610_add_foreign_tasks	1
57	2016_11_11_220649_add_pack_support	1
58	2016_11_11_231731_set_service_name_unique	1
59	2016_11_27_142519_add_pack_column	1
60	2016_12_01_173018_add_configurable_upload_limit	1
61	2016_12_02_185206_correct_service_variables	1
62	2017_01_07_154228_create_node_configuration_tokens_table	1
63	2017_01_12_135449_add_more_user_data	1
64	2017_02_02_175548_UpdateColumnNames	1
65	2017_02_03_140948_UpdateNodesTable	1
66	2017_02_03_155554_RenameColumns	1
67	2017_02_05_164123_AdjustColumnNames	1
68	2017_02_05_164516_AdjustColumnNamesForServicePacks	1
69	2017_02_09_174834_SetupPermissionsPivotTable	1
70	2017_02_10_171858_UpdateAPIKeyColumnNames	1
71	2017_03_03_224254_UpdateNodeConfigTokensColumns	1
72	2017_03_05_212803_DeleteServiceExecutableOption	1
73	2017_03_10_162934_AddNewServiceOptionsColumns	1
74	2017_03_10_173607_MigrateToNewServiceSystem	1
75	2017_03_11_215455_ChangeServiceVariablesValidationRules	1
76	2017_03_12_150648_MoveFunctionsFromFileToDatabase	1
77	2017_03_14_175631_RenameServicePacksToSingluarPacks	1
78	2017_03_14_200326_AddLockedStatusToTable	1
79	2017_03_16_181109_ReOrganizeDatabaseServersToDatabaseHost	1
80	2017_03_16_181515_CleanupDatabasesDatabase	1
81	2017_03_18_204953_AddForeignKeyToPacks	1
82	2017_03_31_221948_AddServerDescriptionColumn	1
83	2017_04_02_163232_DropDeletedAtColumnFromServers	1
84	2017_04_15_125021_UpgradeTaskSystem	1
85	2017_04_20_171943_AddScriptsToServiceOptions	1
86	2017_04_21_151432_AddServiceScriptTrackingToServers	1
87	2017_04_27_145300_AddCopyScriptFromColumn	1
88	2017_04_27_223629_AddAbilityToDefineConnectionOverSSLWithDaemonBehindProxy	1
89	2017_05_01_141528_DeleteDownloadTable	1
90	2017_05_01_141559_DeleteNodeConfigurationTable	1
91	2017_06_10_152951_add_external_id_to_users	1
92	2017_06_25_133923_ChangeForeignKeyToBeOnCascadeDelete	1
93	2017_07_08_152806_ChangeUserPermissionsToDeleteOnUserDeletion	1
94	2017_07_08_154416_SetAllocationToReferenceNullOnServerDelete	1
95	2017_07_08_154650_CascadeDeletionWhenAServerOrVariableIsDeleted	1
96	2017_07_24_194433_DeleteTaskWhenParentServerIsDeleted	1
97	2017_08_05_115800_CascadeNullValuesForDatabaseHostWhenNodeIsDeleted	1
98	2017_08_05_144104_AllowNegativeValuesForOverallocation	1
99	2017_08_05_174811_SetAllocationUnqiueUsingMultipleFields	1
100	2017_08_15_214555_CascadeDeletionWhenAParentServiceIsDeleted	1
101	2017_08_18_215428_RemovePackWhenParentServiceOptionIsDeleted	1
102	2017_09_10_225749_RenameTasksTableForStructureRefactor	1
103	2017_09_10_225941_CreateSchedulesTable	1
104	2017_09_10_230309_CreateNewTasksTableForSchedules	1
105	2017_09_11_002938_TransferOldTasksToNewScheduler	1
106	2017_09_13_211810_UpdateOldPermissionsToPointToNewScheduleSystem	1
107	2017_09_23_170933_CreateDaemonKeysTable	1
108	2017_09_23_173628_RemoveDaemonSecretFromServersTable	1
109	2017_09_23_185022_RemoveDaemonSecretFromSubusersTable	1
110	2017_10_02_202000_ChangeServicesToUseAMoreUniqueIdentifier	1
111	2017_10_02_202007_ChangeToABetterUniqueServiceConfiguration	1
112	2017_10_03_233202_CascadeDeletionWhenServiceOptionIsDeleted	1
113	2017_10_06_214026_ServicesToNestsConversion	1
114	2017_10_06_214053_ServiceOptionsToEggsConversion	1
115	2017_10_06_215741_ServiceVariablesToEggVariablesConversion	1
116	2017_10_24_222238_RemoveLegacySFTPInformation	1
117	2017_11_11_161922_Add2FaLastAuthorizationTimeColumn	1
118	2017_11_19_122708_MigratePubPrivFormatToSingleKey	1
119	2017_12_04_184012_DropAllocationsWhenNodeIsDeleted	1
120	2017_12_12_220426_MigrateSettingsTableToNewFormat	1
121	2018_01_01_122821_AllowNegativeValuesForServerSwap	1
122	2018_01_11_213943_AddApiKeyPermissionColumns	1
123	2018_01_13_142012_SetupTableForKeyEncryption	1
124	2018_01_13_145209_AddLastUsedAtColumn	1
125	2018_02_04_145617_AllowTextInUserExternalId	1
126	2018_02_10_151150_remove_unique_index_on_external_id_column	1
127	2018_02_17_134254_ensure_unique_allocation_id_on_servers_table	1
128	2018_02_24_112356_add_external_id_column_to_servers_table	1
129	2018_02_25_160152_remove_default_null_value_on_table	1
130	2018_02_25_160604_define_unique_index_on_users_external_id	1
131	2018_03_01_192831_add_database_and_port_limit_columns_to_servers_table	1
132	2018_03_15_124536_add_description_to_nodes	1
133	2018_05_04_123826_add_maintenance_to_nodes	1
134	2018_09_03_143756_allow_egg_variables_to_have_longer_values	1
135	2018_09_03_144005_allow_server_variables_to_have_longer_values	1
136	2019_03_02_142328_set_allocation_limit_default_null	1
137	2019_03_02_151321_fix_unique_index_to_account_for_host	1
138	2020_03_22_163911_merge_permissions_table_into_subusers	1
139	2020_03_22_164814_drop_permissions_table	1
140	2020_04_03_203624_add_threads_column_to_servers_table	1
141	2020_04_03_230614_create_backups_table	1
142	2020_04_04_131016_add_table_server_transfers	1
143	2020_04_10_141024_store_node_tokens_as_encrypted_value	1
144	2020_04_17_203438_allow_nullable_descriptions	1
145	2020_04_22_055500_add_max_connections_column	1
146	2020_04_26_111208_add_backup_limit_to_servers	1
147	2020_05_20_234655_add_mounts_table	1
148	2020_05_21_192756_add_mount_server_table	1
149	2020_07_02_213612_create_user_recovery_tokens_table	1
150	2020_07_09_201845_add_notes_column_for_allocations	1
151	2020_08_20_205533_add_backup_state_column_to_backups	1
152	2020_08_22_132500_update_bytes_to_unsigned_bigint	1
153	2020_08_23_175331_modify_checksums_column_for_backups	1
154	2020_09_13_110007_drop_packs_from_servers	1
155	2020_09_13_110021_drop_packs_from_api_key_permissions	1
156	2020_09_13_110047_drop_packs_table	1
157	2020_09_13_113503_drop_daemon_key_table	1
158	2020_10_10_165437_change_unique_database_name_to_account_for_server	1
159	2020_10_26_194904_remove_nullable_from_schedule_name_field	1
160	2020_11_02_201014_add_features_column_to_eggs	1
161	2020_12_12_102435_support_multiple_docker_images_and_updates	1
162	2020_12_14_013707_make_successful_nullable_in_server_transfers	1
163	2020_12_17_014330_add_archived_field_to_server_transfers_table	1
164	2020_12_24_092449_make_allocation_fields_json	1
165	2020_12_26_184914_add_upload_id_column_to_backups_table	1
166	2021_01_10_153937_add_file_denylist_to_egg_configs	1
167	2021_01_13_013420_add_cron_month	1
168	2021_01_17_102401_create_audit_logs_table	1
169	2021_01_17_152623_add_generic_server_status_column	1
170	2021_01_26_210502_update_file_denylist_to_json	1
171	2021_02_23_205021_add_index_for_server_and_action	1
172	2021_02_23_212657_make_sftp_port_unsigned_int	1
173	2021_03_21_104718_force_cron_month_field_to_have_value_if_missing	1
174	2021_05_01_092457_add_continue_on_failure_option_to_tasks	1
175	2021_05_01_092523_add_only_run_when_server_online_option_to_schedules	1
176	2021_05_03_201016_add_support_for_locking_a_backup	1
177	2021_07_12_013420_remove_userinteraction	1
178	2021_07_17_211512_create_user_ssh_keys_table	1
179	2021_08_03_210600_change_successful_field_to_default_to_false_on_backups_table	1
180	2021_08_21_175111_add_foreign_keys_to_mount_node_table	1
181	2021_08_21_175118_add_foreign_keys_to_mount_server_table	1
182	2021_08_21_180921_add_foreign_keys_to_egg_mount_table	1
183	2022_01_25_030847_drop_google_analytics	1
184	2022_05_07_165334_migrate_egg_images_array_to_new_format	1
185	2022_05_28_135717_create_activity_logs_table	1
186	2022_05_29_140349_create_activity_log_actors_table	1
187	2022_06_18_112822_track_api_key_usage_for_activity_events	1
188	2022_08_16_214400_add_force_outgoing_ip_column_to_eggs_table	1
189	2022_08_16_230204_add_installed_at_column_to_servers_table	1
190	2023_01_24_210051_add_uuid_column_to_failed_jobs_table	1
191	2023_02_23_191004_add_expires_at_column_to_api_keys_table	1
192	2024_03_12_154408_remove_nests_table	1
193	2024_03_14_055537_remove_locations_table	1
194	2024_04_14_002250_update_column_names	1
195	2024_04_20_214441_add_egg_var_sort	1
196	2024_04_21_162544_create_webhook_configurations_table	1
197	2024_04_21_162552_create_webhooks_table	1
198	2024_04_28_184102_add_mounts_to_api_keys	1
199	2024_05_08_094823_rename_oom_disabled_column_to_oom_killer	1
200	2024_05_16_091207_add_cpu_columns_to_nodes_table	1
201	2024_05_20_002841_add_docker_container_label	1
202	2024_05_31_204646_fix_old_encrypted_values	1
203	2024_06_02_205622_update_stock_egg_uuid	1
204	2024_06_04_085042_add_daemon_sftp_alias_column_to_nodes	1
205	2024_06_04_133434_make_allowed_ips_column_non_nullable	1
206	2024_06_04_212155_add_timezone_column	1
207	2024_06_05_220135_update_egg_config_variables	1
208	2024_06_08_020904_refix_egg_variables	1
209	2024_06_11_220722_update_field_length	1
210	2024_06_13_120409_add_oauth_column_to_users	1
211	2024_07_08_112948_fix-activity-log-timestamp-default	1
212	2024_07_19_130942_create_permission_tables	1
213	2024_07_25_072050_convert_rules_to_array	1
214	2024_08_01_114538_remove_root_admin_column	1
215	2024_10_27_033218_update_webhook_configurations_softdelete	1
216	2024_10_31_203540_change_database_hosts_to_belong_to_many_nodes	1
217	2024_11_04_185326_revamp_api_keys_permissions	1
218	2024_12_02_013000_remove_illegal_subusers	1
219	2024_12_27_135435_delete_database_host_node_when_node_is_deleted	1
220	2025_01_03_210426_remove_user_first_and_last_names	1
221	2025_01_08_052636_add_driver_to_database_hosts	1
222	2025_01_08_111850_setup_default_postgresql_permissions	1
\.


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 222, true);


--
-- PostgreSQL database dump complete
--

