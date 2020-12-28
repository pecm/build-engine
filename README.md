# Build Engine

The Build Engine is a library that can be used to map a complex build process into a structured set of value objects.

This library is part of the [php-ext.com](https://php-ext.com) portal.

## Concepts

### Environment Variables

TBD.

### Commands

TBD.

### Steps

Steps are the basic building blocks of a build and are self-contained, with a single purpose and a single responsibility.

A step can be as simple as changing the current working directory or as complex as cloning a repository or downloading a remote file.

Steps are defined in terms of commands *****

### Stages

Stages are a more sophisticated block of a build, created by the combination of multiple steps, with a target goal.

A stage can be, for instance, build a library from its source code.

Note that a stage requires each step to be successful before executing the next step in the stage's step chain.

### Pipelines

Pipelines are a full-blown build, composed by one or more stages.

A pipeline can be, for instance, download operating system dependencies required to build a library from its source code and then build it.

Note that a pipeline requires each stage to be successful before executing the next stage in the pipeline.
