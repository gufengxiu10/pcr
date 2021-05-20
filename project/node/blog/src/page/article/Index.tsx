import React from "react";
import { Data, State } from "./index.type";
import { Table, Modal, Button } from "antd";
import axioas from "axios";

import { Link } from "react-router-dom";

export default class Article extends React.Component {
  public state: State = {
    visible: false,
    data: [],
  };

  columns: Array<{}> = [
    {
      title: "编号",
      dataIndex: "id",
    },
    {
      title: "标题",
      className: "column-money",
      dataIndex: "title",
    },
    {
      titel: "操作",
      render: (text: Data, row: Data, index: number) => {
        const url = "id/" + row.id;
        return (
          <Link to={url}>
            <Button type="text">编辑</Button>
          </Link>
        );
      },
    },
  ];

  constructor(props: {}) {
    super(props);
    this.modalShow = this.modalShow.bind(this);
    this.modalHide = this.modalHide.bind(this);
  }

  componentDidMount() {
    this.list();
  }

  modalShow() {
    this.setState({ visible: true });
  }

  modalHide() {
    this.setState({ visible: false });
  }

  async list() {
    const data = await axioas.get("/article");
    data.data.forEach((item: Data) => {
      item.key = item.id;
    });
    this.setState({ data: data.data });
  }

  render() {
    return (
      <>
        <Table columns={this.columns} dataSource={this.state.data} bordered />
      </>
    );
  }
}
