# Changelog

All notable changes to `php-code-search` will be documented in this file.

---

## 1.6.1 - 2021-07-22

- fix issue with function call node names

## 1.6.0 - 2021-07-07

- all function call, static method call, method call nodes have an `args` property containing the value node(s) of the parsed arguments.
- assignment nodes now have a `value` property and `value()` method.
- strings and numbers are converted to `StringNode` and `NumberNode` nodes, respectively.
- most values converted to Node classes that implement either `ResultNode`, `ValueNode`, or both.
- operations (addition, concat, etc.) converted to Node classes that implement `OperationNode`.
- fixed several bugs related to non-matches being returned as matches.

## 1.5.3 - 2021-07-07

- fix issues with `Assignment` nodes

## 1.5.2 - 2021-07-07

- fix issues with `Array`, `ArrayItem` and `ArrayDimFetch` nodes

## 1.5.1 - 2021-07-06

- internal refactoring

## 1.5.0 - 2021-07-06

- rename `FunctionCallLocation` to `GenericCodeLocation` and remove the name property

## 1.4.0 - 2021-07-05

- allow searching for static method calls like `MyClass` or `MyClass::someMethod`
- add `ResultNode` class
- add `node` property to `SearchResult` class

## 1.3.2 - 2021-07-05

- minor fix to method searching

## 1.3.1 - 2021-07-05

- minor fix to variable searching

## 1.3.0 - 2021-07-05

- add `methods` method
- add `variables` method

## 1.2.1 - 2021-07-05

- fix function search feature

## 1.2.0 - 2021-07-04

- add `searchCode` method

## 1.1.1 - 2021-07-04

- add `filename` property to `File` class

## 1.1.0 - 2021-07-04

- add `file` property to `SearchResult` class

## 1.0.0 - 2021-07-04

- initial release

